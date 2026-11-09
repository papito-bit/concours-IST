<?php session_start();
if(isset($_SESSION['connexion'])){
   echo "1";
  header("Location:index.php");}
  elseif (isset($_SESSION['connexion2'])) {
     echo "2";
    header("Location:index.php");
  }
  elseif(isset($_POST['connexion'])){
     echo "3";
     
      $email=htmlspecialchars($_POST['email']);
      $password=sha1(md5($_POST['password']));
      include 'connect.php';
      $user=$bdd->prepare('SELECT `id`, `email`, `password`, `Nom`, `Prenom`, `type` FROM users WHERE email=? AND password=?');
      $user->execute(array($email,$password));$userDetected=$user->fetch();
      $userCompte = $user->rowCount();
      if($userCompte!=0 AND $userDetected['type']=="Admin"){
        $_SESSION['connexion']=$connected=array(
        'id'=>$userDetected['id'],
        'email'=>$email,
        'password'=>$password,
        'nom'=>$userDetected['Nom'],
        'prenom'=>$userDetected['Prenom']);
        header("Location:index.php");}
        elseif ($userCompte!=0 AND $userDetected['type']=="Users") {
          $_SESSION['connexion2']=$connected=array(
          'id'=>$userDetected['id'],
          'email'=>$email,
          'password'=>$password);
          header("Location:index.php");
        }
        else{$erreur="adresse mail ou mot de passe incorrect.";}
      } 

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SCOLARIST</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="css/fontastic.css">
    <!-- Google fonts - Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/favicon.ico">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    <div class="page login-page">
      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">
          <div class="row">
            <!-- Logo & Information Panel-->
            <div class="col-lg-6">
              <div class="info d-flex align-items-center">
                <div class="content">
                  <div class="logo">
                    <h1>Service Scolarité</h1>
                  </div>
                  <p>Gestion de scolarité</p>
                </div>
              </div>
            </div>
            <!-- Form Panel    -->
            <div class="col-lg-6 bg-white">
              <div class="form d-flex align-items-center">
                <div class="content">
                  <form method="post" class="form-validate" action="">
                    <div class="form-group">
                      <input id="login-username" type="text" name="email" required data-msg="Veuillez entrer votre nom d'utilisateur" class="input-material" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>">
                      <label for="login-username" class="label-material">Nom d'utilisateur</label>
                    </div>
                    <div class="form-group">
                      <input id="login-password" type="password" name="password" required data-msg="Veuillez entrer votre mot de passe" class="input-material" value="<?php if(isset($_POST['password'])){echo $_POST['password'];} ?>">
                      <label for="login-password" class="label-material">Mot de passe</label>
                    </div><input type="submit" id="login" href="index.html" class="btn btn-primary" name="connexion" value="Connexion"/>
                    <!-- This should be submit button but I replaced it with <a> for demo purposes-->
                  </form><a href="#" class="forgot-pass">Mot de passe oublier ?</a><br><small>Pas de compte ? </small><a href="register.html" class="signup">S'inscrire</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="copyrights text-center">
        <p>Design by <a href="https://bootstrapious.com/p/admin-template" class="external">Victorien SOANY</a>
          <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
        </p>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <!-- Main File-->
    <script src="js/front.js"></script>
  </body>
</html>
<?php if(isset($erreur)){echo($erreur);} ?>