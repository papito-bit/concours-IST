<?php session_start(); session_destroy(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mot de passe oublié | Concours IST-D</title>
  <link rel="icon" type="image/png" href="../../assets/img/logo_ist.png">
  <link rel="stylesheet" href="../../assets/plugins/fontawesome-free/css/all.min.css">
  <style>
    * { box-sizing: border-box; }
    body {
      min-height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center;
      padding: 1.5rem; background: #f8fcfe; color: #12303a;
      font-family: "Source Sans Pro", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    }
    .boite { width: 100%; max-width: 460px; background: #fff; border: 1px solid #dcedf3; border-top: 4px solid #00b4d8; padding: 2rem; }
    .boite img { width: 72px; height: 72px; object-fit: contain; display: block; margin: 0 auto 1rem; }
    h1 { margin: 0 0 .4rem; text-align: center; font-size: 1.5rem; color: #0a6c85; }
    .trait { width: 40px; height: 3px; margin: .6rem auto 1.4rem; background: #d62f37; }
    p { line-height: 1.55; color: #3b6573; margin: 0 0 1rem; }
    ol { margin: 0 0 1.4rem; padding-left: 1.2rem; color: #3b6573; line-height: 1.7; }
    .bouton {
      display: block; text-align: center; text-decoration: none; padding: .8rem 1rem;
      background: #0096b7; color: #fff; font-weight: 700; border-radius: 4px;
    }
    .bouton:hover { background: #007f9c; }
  </style>
</head>
<body>
  <main class="boite">
    <img src="../../assets/img/logo_ist.png" alt="Logo IST Antsiranana">
    <h1>Mot de passe oublié ?</h1>
    <div class="trait"></div>
    <p>Pour des raisons de sécurité, le mot de passe ne se réinitialise pas depuis cette page.</p>
    <ol>
      <li>Contactez un administrateur de la plateforme.</li>
      <li>Il ouvrira <strong>Utilisateurs</strong>, puis <strong>Modifier</strong> sur votre compte.</li>
      <li>Il y définira un nouveau mot de passe, que vous pourrez ensuite utiliser pour vous connecter.</li>
    </ol>
    <a class="bouton" href="../../login.php"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
  </main>
</body>
</html>
