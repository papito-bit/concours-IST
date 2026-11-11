<?php session_start(); session_destroy(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion | Concours IST-D</title>
  <link rel="icon" type="image/png" href="img/logo_ist.png">

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <style>
    :root {
      --piscine: #0A8FB0;
      --piscine-fonce: #077391;
      --piscine-profond: #0B6580;
      --piscine-pale: #E3F5FA;
      --rouge: #E5383B;
      --encre: #17323D;
      --gris: #5E7782;
    }
    * { box-sizing: border-box; }
    body {
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: "Source Sans Pro", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
      background: var(--piscine-profond);
      padding: 1.5rem;
    }
    .connexion {
      width: 100%;
      max-width: 400px;
      padding: 2.2rem 2rem 1.6rem;
      border-radius: 4px;
      border-top: 4px solid var(--rouge);
      background: #fff;
    }
    .connexion .logo {
      display: block;
      width: 80px;
      height: 80px;
      margin: 0 auto 1rem;
      object-fit: contain;
    }
    .connexion h1 {
      margin: 0;
      text-align: center;
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--piscine-profond);
    }
    .connexion h1 span { color: var(--rouge); }
    .connexion .sous-titre {
      margin: .35rem 0 1.6rem;
      text-align: center;
      color: var(--gris);
      font-size: .95rem;
    }
    .champ { position: relative; margin-bottom: 1rem; }
    .champ i.icone {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--piscine);
    }
    .champ input {
      width: 100%;
      height: 46px;
      padding: 0 46px 0 42px;
      border: 1px solid #BBD6DF;
      border-radius: 4px;
      background: #fff;
      color: var(--encre);
      font-size: 1rem;
    }
    .champ input:focus {
      outline: none;
      border-color: var(--piscine);
      box-shadow: 0 0 0 3px rgba(10, 143, 176, .18);
    }
    .champ .oeil {
      position: absolute;
      right: 5px;
      top: 50%;
      transform: translateY(-50%);
      width: 36px;
      height: 36px;
      border: 0;
      border-radius: 4px;
      background: transparent;
      color: var(--gris);
      cursor: pointer;
    }
    .champ .oeil:hover { color: var(--piscine-fonce); background: var(--piscine-pale); }
    .erreur {
      margin-bottom: 1rem;
      padding: .65rem .9rem;
      border-left: 4px solid var(--rouge);
      border-radius: 4px;
      background: #FDECEC;
      color: #C92A2D;
      font-size: .92rem;
      font-weight: 600;
    }
    .bouton {
      width: 100%;
      height: 46px;
      border: 0;
      border-radius: 4px;
      background: var(--piscine);
      color: #fff;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
    }
    .bouton:hover { background: var(--piscine-fonce); }
    .bouton:disabled { opacity: .75; cursor: wait; }
    .liens { margin-top: 1.1rem; text-align: center; font-size: .92rem; }
    .liens a { color: var(--piscine-fonce); font-weight: 600; text-decoration: none; }
    .liens a:hover { text-decoration: underline; }
    .pied { margin-top: 1.4rem; text-align: center; color: var(--gris); font-size: .8rem; }
  </style>
</head>
<body>

<main class="connexion">
  <img class="logo" src="img/logo_ist.png" alt="Logo de l'IST-D">
  <h1><span>Concours</span> IST-D</h1>
  <p class="sous-titre">Authentifiez-vous pour accéder à la gestion des concours.</p>

  <?php if (isset($_GET['erreur'])): ?>
    <div class="erreur"><i class="fas fa-exclamation-circle"></i> Identifiant ou mot de passe incorrect.</div>
  <?php endif; ?>

  <form action="login_traitement.php" method="post" id="formConnexion">
    <div class="champ">
      <i class="fas fa-user icone"></i>
      <input type="text" name="email_concours" placeholder="Identifiant" required autofocus autocomplete="username">
    </div>
    <div class="champ">
      <i class="fas fa-lock icone"></i>
      <input type="password" name="mdp_concours" placeholder="Mot de passe" required autocomplete="current-password" id="motDePasse">
      <button type="button" class="oeil" id="boutonOeil" aria-label="Afficher ou masquer le mot de passe">
        <i class="fas fa-eye" id="iconeOeil"></i>
      </button>
    </div>
    <button type="submit" class="bouton" id="boutonValider">Se connecter</button>
  </form>

  <p class="liens"><a href="mot_de_passe_oublie.php">Mot de passe oublié ?</a></p>
  <p class="pied">Institut Supérieur de Technologie d'Antsiranana</p>
</main>

<script>
  // Afficher ou masquer le mot de passe
  document.getElementById('boutonOeil').addEventListener('click', function () {
    var champ = document.getElementById('motDePasse');
    var icone = document.getElementById('iconeOeil');
    var visible = champ.type === 'text';
    champ.type = visible ? 'password' : 'text';
    icone.className = visible ? 'fas fa-eye' : 'fas fa-eye-slash';
  });
  // Empêche le double clic pendant la connexion
  document.getElementById('formConnexion').addEventListener('submit', function () {
    var b = document.getElementById('boutonValider');
    b.disabled = true;
    b.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Connexion en cours...';
  });
</script>
</body>
</html>
