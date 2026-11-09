<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Mot de passe oublié - Concours IST-D</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.min.css" />

  <style>
    .login-logo img {
      width: 80px;
      height: auto;
      margin-bottom: 10px;
    }
    .login-box {
      width: 360px;
    }
    #messageBox {
      margin-top: 10px;
    }
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
      <p class="login-box-msg">
        Réinitialisation du mot de passe
      </p>
      <p class="text-muted small text-center">
        Entrez votre email ou identifiant, nous vous enverrons un lien pour réinitialiser votre mot de passe.
      </p>

      <form id="resetForm" method="post">
        <div class="input-group mb-3">
          <input
            type="email"
            class="form-control"
            placeholder="Votre email"
            name="email"
            required
            autofocus
            id="emailInput"
          />
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
          Envoyer le lien
        </button>
      </form>

      <div id="messageBox" class="text-center"></div>

      <p class="mt-3 mb-0 text-center">
        <a href="login.php"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
      </p>
    </div>
  </div>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
  $('#resetForm').on('submit', function (e) {
    e.preventDefault();

    // Affiche message chargement
    $('#messageBox').html('<span style="color:blue;">Chargement...</span>');

    const email = $('#emailInput').val();

    $.ajax({
      url: 'reset_password_traitement.php',
      method: 'POST',
      data: { email: email },
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          $('#messageBox').html('<span style="color:green;">' + response.message + '</span>');
        } else {
          $('#messageBox').html(
            '<span style="color:red;">Une erreur est survenue, veuillez réessayer. Si le problème persiste, contactez l\'administrateur.</span>'
          );
        }
      },
      error: function () {
        $('#messageBox').html(
          '<span style="color:red;">Une erreur est survenue, veuillez réessayer. Si le problème persiste, contactez l\'administrateur.</span>'
        );
      },
    });
  });
</script>
</body>
</html>
