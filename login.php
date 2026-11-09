<?php session_start();session_destroy(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Concours IST-D</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <style>
    body {
      background: url('img/bg_ist.jpg') no-repeat center center fixed;
      background-size: cover;
    }
    .overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.4);
    }
    .login-logo img {
      width: 80px;
      height: auto;
      margin-bottom: 10px;
    }
    .login-box {
      z-index: 2;
      width: 360px;
    }
    .show-pass {
      cursor: pointer;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="overlay"></div>

<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <div class="login-logo">
        <img src="img/logo_ist.png" alt="Logo IST">
      </div>
      <a href="#" style="color: #ff1d15;" class="h1"><b>Concours</b> IST-D</a>
    </div>

    <div class="card-body">
      <p class="login-box-msg">
        <b>Année <?php echo date("Y") . "-" . (date("Y") + 1); ?></b>
      </p>

      <form action="login_traitement.php" method="post">
        <!-- Champ identifiant -->
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Email ou identifiant" name="email_concours" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>

        <!-- Champ mot de passe avec bouton œil -->
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Mot de passe" name="mdp_concours" required autocomplete="off" id="passwordField">
          <div class="input-group-append">
            <div class="input-group-text show-pass" onclick="togglePassword()">
              <span class="fas fa-eye" id="toggleIcon"></span>
            </div>
          </div>
        </div>

        <!-- Message d'erreur -->
        <?php if (isset($_GET['erreur'])): ?>
          <div class="alert alert-danger p-1 text-center">
            Mauvais identifiant ou mot de passe !
          </div>
        <?php endif; ?>

        <!-- Bouton -->
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Valider</button>
          </div>
        </div>
      </form>

      <!-- Option lien mot de passe oublié -->
      <p class="mb-0 mt-3 text-center">
        <a href="mot_de_passe_oublie.php">Mot de passe oublié ?</a>
      </p>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- Script pour afficher/masquer le mot de passe -->
<script>
  function togglePassword() {
    const pwdField = document.getElementById('passwordField');
    const icon = document.getElementById('toggleIcon');
    if (pwdField.type === "password") {
      pwdField.type = "text";
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      pwdField.type = "password";
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }
</script>

</body>
</html>
