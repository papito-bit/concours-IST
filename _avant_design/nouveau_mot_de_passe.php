<?php
session_start();
require_once 'config.php'; // Connexion PDO

// Vérifier la présence du token dans l'URL
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Lien de réinitialisation invalide.");
}

$token = $_GET['token'];
$errors = [];
$success = false;

// Vérifier que le token est valide et non expiré
$stmt = $pdo->prepare("SELECT id, reset_expires FROM concours_utilisateurs WHERE reset_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Lien de réinitialisation invalide ou expiré.");
}

$expires = strtotime($user['reset_expires']);
if ($expires < time()) {
    die("Le lien de réinitialisation a expiré. Veuillez en faire une nouvelle demande.");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mdp1 = $_POST['mdp1'] ?? '';
    $mdp2 = $_POST['mdp2'] ?? '';

    if (empty($mdp1) || empty($mdp2)) {
        $errors[] = "Veuillez remplir tous les champs.";
    } elseif ($mdp1 !== $mdp2) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($mdp1) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        // Hacher le nouveau mot de passe
        $hash = password_hash($mdp1, PASSWORD_DEFAULT);

        // Mettre à jour le mot de passe en BDD et supprimer token
        $stmt = $pdo->prepare("UPDATE concours_utilisateurs SET mdp_concours = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        if ($stmt->execute([$hash, $user['id']])) {
            $success = true;
        } else {
            $errors[] = "Erreur lors de la mise à jour du mot de passe. Veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Réinitialiser mot de passe - Concours IST-D</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet" />
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
  <!-- icheck bootstrap -->
  <link href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css" rel="stylesheet" />
  <!-- Theme style -->
  <link href="dist/css/adminlte.min.css" rel="stylesheet" />

  <style>
    .login-box { width: 360px; }
    .login-logo img { width: 80px; margin-bottom: 10px; }
  </style>
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <div class="login-logo">
        <img src="img/logo_ist.png" alt="Logo IST" />
      </div>
      <a href="#" style="color: #ff1d15;" class="h1"><b>Concours</b> IST-D</a>
    </div>

    <div class="card-body">
      <p class="login-box-msg">Choisissez un nouveau mot de passe</p>

      <?php if ($success): ?>
        <div class="alert alert-success text-center">
          Mot de passe mis à jour avec succès.<br />
          <a href="login.php">Cliquez ici pour vous connecter</a>
        </div>
      <?php else: ?>
        <?php if ($errors): ?>
          <div class="alert alert-danger">
            <ul>
              <?php foreach ($errors as $err): ?>
                <li><?=htmlspecialchars($err)?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form method="post" action="">
          <div class="input-group mb-3">
            <input
              type="password"
              class="form-control"
              placeholder="Nouveau mot de passe"
              name="mdp1"
              required
              minlength="6"
              autocomplete="new-password"
              id="password1"
            />
            <div class="input-group-append">
              <div class="input-group-text" onclick="togglePassword('password1','toggleIcon1')" style="cursor:pointer;">
                <span class="fas fa-eye" id="toggleIcon1"></span>
              </div>
            </div>
          </div>

          <div class="input-group mb-3">
            <input
              type="password"
              class="form-control"
              placeholder="Confirmer mot de passe"
              name="mdp2"
              required
              minlength="6"
              autocomplete="new-password"
              id="password2"
            />
            <div class="input-group-append">
              <div class="input-group-text" onclick="togglePassword('password2','toggleIcon2')" style="cursor:pointer;">
                <span class="fas fa-eye" id="toggleIcon2"></span>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">Mettre à jour</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
function togglePassword(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon = document.getElementById(iconId);
  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
}
</script>

</body>
</html>
