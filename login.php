<?php session_start(); session_destroy(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion | Concours IST-D</title>
  <link rel="icon" type="image/png" href="assets/img/logo_ist.png">
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">

  <style>
    * { box-sizing: border-box; }
    html, body { height: 100%; margin: 0; }
    body {
      display: flex;
      font-family: "Source Sans Pro", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
      color: #12303a;
      background: #fff;
    }
    /* Panneau gauche : identité */
    .identite {
      flex: 1 1 46%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 3rem;
      background: #eaf7fb;
      color: #12303a;
      border-right: 6px solid #00b4d8;
    }
    .identite img { width: 96px; height: 96px; object-fit: contain; }
    .identite h1 { margin: 1.4rem 0 .4rem; font-size: 2.2rem; font-weight: 700; letter-spacing: .01em; }
    .identite h1 span { color: #0096b7; }
    .identite p { margin: 0; max-width: 360px; color: #3b6573; font-size: 1.05rem; line-height: 1.5; }
    .identite .trait { width: 48px; height: 4px; margin: 1.2rem 0; background: #d62f37; }
    .identite ul { margin: 1.6rem 0 0; padding: 0; list-style: none; color: #3b6573; font-size: .98rem; }
    .identite li { margin-bottom: .55rem; }
    .identite li i { width: 26px; color: #0096b7; }

    /* Panneau droit : formulaire */
    .formulaire {
      flex: 1 1 54%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    .boite { width: 100%; max-width: 380px; }
    .boite h2 { margin: 0 0 .3rem; font-size: 1.6rem; font-weight: 700; color: #0a6c85; }
    .boite .sous-titre { margin: 0 0 1.8rem; color: #5f7c86; }
    .champ { margin-bottom: 1.1rem; }
    .champ label { display: block; margin-bottom: .35rem; font-weight: 600; font-size: .9rem; color: #2f4f5a; }
    .saisie { position: relative; }
    .saisie i.icone { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #7d9aa4; }
    .saisie input {
      width: 100%;
      height: 46px;
      padding: 0 44px 0 40px;
      border: 1px solid #c5dce4;
      border-radius: 4px;
      background: #fff;
      font-size: 1rem;
      color: #12303a;
    }
    .saisie input:focus { outline: 2px solid rgba(0, 180, 216, .35); border-color: #00b4d8; }
    .saisie .oeil {
      position: absolute; right: 0; top: 0; width: 44px; height: 46px;
      border: 0; background: transparent; color: #7d9aa4; cursor: pointer;
    }
    .saisie .oeil:hover { color: #0096b7; }
    .erreur {
      margin-bottom: 1.1rem; padding: .7rem .9rem;
      border: 1px solid #f3b9bc; border-left: 4px solid #d62f37;
      background: #fdeceb; color: #8f1d23; font-size: .93rem; font-weight: 600;
    }
    .bouton {
      width: 100%; height: 46px; border: 0; border-radius: 4px;
      background: #0096b7; color: #fff; font-size: 1rem; font-weight: 700; cursor: pointer;
    }
    .bouton:hover { background: #007f9c; }
    .bouton:disabled { background: #7fc3d3; cursor: wait; }
    .liens { margin: 1.2rem 0 0; font-size: .93rem; }
    .liens a { color: #0096b7; font-weight: 600; text-decoration: none; }
    .liens a:hover { text-decoration: underline; }
    .pied { margin-top: 2.2rem; color: #7d9aa4; font-size: .82rem; }

    @media (max-width: 800px) {
      body { flex-direction: column; height: auto; min-height: 100%; }
      .identite { flex: none; padding: 2rem 1.5rem; border-right: 0; border-bottom: 6px solid #00b4d8; }
      .identite ul { display: none; }
      .identite h1 { font-size: 1.7rem; margin-top: 1rem; }
      .identite img { width: 72px; height: 72px; }
    }
  </style>
</head>
<body>

<section class="identite">
  <img src="assets/img/logo_ist.png" alt="Logo IST Antsiranana">
  <h1><span>Concours</span> IST-D</h1>
  <div class="trait"></div>
  <p>Plateforme de gestion du concours d'entrée de l'Institut Supérieur de Technologie d'Antsiranana.</p>
  <ul>
    <li><i class="fas fa-user-graduate"></i> Inscription et suivi des candidats</li>
    <li><i class="fas fa-print"></i> Convocations et listes de présence</li>
    <li><i class="fas fa-chart-bar"></i> Statistiques et délibération</li>
  </ul>
</section>

<section class="formulaire">
  <div class="boite">
    <h2>Connexion</h2>
    <p class="sous-titre">Identifiez-vous pour accéder à votre espace.</p>

    <?php if (isset($_GET['erreur'])): ?>
      <div class="erreur"><i class="fas fa-exclamation-circle"></i> Identifiant ou mot de passe incorrect.</div>
    <?php endif; ?>

    <form action="modules/auth/login_traitement.php" method="post" id="formConnexion">
      <div class="champ">
        <label for="identifiant">Identifiant</label>
        <div class="saisie">
          <i class="fas fa-user icone"></i>
          <input type="text" id="identifiant" name="email_concours" required autofocus autocomplete="username">
        </div>
      </div>
      <div class="champ">
        <label for="motDePasse">Mot de passe</label>
        <div class="saisie">
          <i class="fas fa-lock icone"></i>
          <input type="password" id="motDePasse" name="mdp_concours" required autocomplete="current-password">
          <button type="button" class="oeil" id="boutonOeil" aria-label="Afficher ou masquer le mot de passe">
            <i class="fas fa-eye" id="iconeOeil"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="bouton" id="boutonValider">Se connecter</button>
    </form>

    <p class="liens"><a href="modules/auth/mot_de_passe_oublie.php">Mot de passe oublié ?</a></p>
    <p class="pied">&copy; IST Antsiranana</p>
  </div>
</section>

<script>
  document.getElementById('boutonOeil').addEventListener('click', function () {
    var champ = document.getElementById('motDePasse');
    var icone = document.getElementById('iconeOeil');
    var visible = champ.type === 'text';
    champ.type = visible ? 'password' : 'text';
    icone.className = visible ? 'fas fa-eye' : 'fas fa-eye-slash';
  });
  document.getElementById('formConnexion').addEventListener('submit', function () {
    var b = document.getElementById('boutonValider');
    b.disabled = true;
    b.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Connexion en cours';
  });
</script>
</body>
</html>
