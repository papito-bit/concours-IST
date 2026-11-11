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
    * { box-sizing: border-box; }
    body {
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: "Source Sans Pro", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
      background:
        linear-gradient(135deg, rgba(11, 18, 32, .86) 0%, rgba(30, 58, 138, .62) 100%),
        url('img/bg_ist_light.jpg') no-repeat center center fixed;
      background-size: cover;
      padding: 1.5rem;
    }
    .connexion {
      width: 100%;
      max-width: 400px;
      padding: 2.2rem 2rem 1.6rem;
      border-radius: 20px;
      background: rgba(255, 255, 255, .95);
      box-shadow: 0 30px 80px rgba(0, 0, 0, .4);
      animation: monter .5s ease both;
    }
    @keyframes monter { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }
    .connexion .logo {
      display: block;
      width: 84px;
      height: 84px;
      margin: 0 auto 1rem;
      object-fit: contain;
    }
    .connexion h1 {
      margin: 0;
      text-align: center;
      font-size: 1.7rem;
      font-weight: 700;
      color: #0f172a;
    }
    .connexion h1 span { color: #e11d48; }
    .connexion .sous-titre {
      margin: .35rem 0 1.6rem;
      text-align: center;
      color: #64748b;
      font-size: .95rem;
    }
    .champ { position: relative; margin-bottom: 1rem; }
    .champ i.icone {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
    }
    .champ input {
      width: 100%;
      height: 48px;
      padding: 0 46px 0 42px;
      border: 1px solid #dbe3ee;
      border-radius: 12px;
      background: #f8fafc;
      font-size: 1rem;
      transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .champ input:focus {
      outline: none;
      background: #fff;
      border-color: #2563eb;
      box-shadow: 0 0 0 4px rgba(37, 99, 235, .15);
    }
    .champ .oeil {
      position: absolute;
      right: 6px;
      top: 50%;
      transform: translateY(-50%);
      width: 38px;
      height: 38px;
      border: 0;
      border-radius: 10px;
      background: transparent;
      color: #94a3b8;
      cursor: pointer;
    }
    .champ .oeil:hover { color: #2563eb; background: #eef2ff; }
    .erreur {
      margin-bottom: 1rem;
      padding: .65rem .9rem;
      border-radius: 12px;
      background: #fee2e2;
      color: #b91c1c;
      font-size: .92rem;
      font-weight: 600;
      text-align: center;
    }
    .bouton {
      width: 100%;
      height: 48px;
      border: 0;
      border-radius: 12px;
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: #fff;
      font-size: 1.02rem;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 10px 24px rgba(37, 99, 235, .35);
      transition: transform .12s ease, box-shadow .12s ease, opacity .12s;
    }
    .bouton:hover { transform: translateY(-1px); box-shadow: 0 14px 30px rgba(37, 99, 235, .45); }
    .bouton:disabled { opacity: .75; cursor: wait; transform: none; }
    .liens { margin-top: 1.1rem; text-align: center; font-size: .92rem; }
    .liens a { color: #2563eb; font-weight: 600; text-decoration: none; }
    .liens a:hover { text-decoration: underline; }
    .pied { margin-top: 1.4rem; text-align: center; color: #94a3b8; font-size: .8rem; }
    @media (prefers-reduced-motion: reduce) { .connexion { animation: none; } }
  </style>
</head>
<body>

<main class="connexion">
  <img class="logo" src="img/logo_ist.png" alt="Logo IST">
  <h1><span>Concours</span> IST-D</h1>
  <p class="sous-titre">Connecte-toi pour accéder à la gestion du concours</p>

  <?php if (isset($_GET['erreur'])): ?>
    <div class="erreur"><i class="fas fa-exclamation-circle"></i> Mauvais identifiant ou mot de passe</div>
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
  <p class="pied">IST Antsiranana</p>
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
    b.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Connexion...';
  });
</script>
</body>
</html>
