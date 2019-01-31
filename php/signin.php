<?php
session_start();
if(!array_key_exists('err',$_SESSION)){
	$_SESSION["err"]=NULL;
}
if(array_key_exists('email',$_SESSION)){
	header('location: logged.php');
}
?>
<!DOCTYPE html>
<html lang="de">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Stopwatch | Anmelden</title>

    <!-- Bootstrap core CSS -->
    <link href="././vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="././vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Plugin CSS -->
    <link href="././vendor/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="././css/freelancer.min.css" rel="stylesheet">

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg bg-secondary fixed-top text-uppercase" id="mainNav">
      <div class="container">
        <a class="navbar-brand " href="../"><i class="fas fa-clock"></i></a>
        </div>
      </div>
    </nav>

   <!-- Header -->
    <header class="masthead bg-primary text-white text-center">
      <div class="container">
        <h1 class="text-uppercase mb-0">Anmelden</h1>
      </div>
    </header>
	
    <!-- Login Section -->
    <section id="signin">
      <div class="container">
        <div class="row">
		  <?php
		  if($_SESSION["err"]==3){
			  echo '<div class="col-lg-8 mx-auto alert alert-danger">Bitte Zugangsdaten eingeben.</div>';
		  }
		  if($_SESSION["err"]==1){
			  echo '<div class="col-lg-8 mx-auto alert alert-danger">E-Mail Adresse nicht vorhanden.</div>';
		  }
		  if($_SESSION["err"]==2){
			  echo '<div class="col-lg-8 mx-auto alert alert-danger">Das eingegebene Passwort ist falsch. Bitte nochmal genau überlegen.</div>';
		  }
		  session_destroy();
		  ?>
          <div class="col-lg-8 mx-auto">
            <form name="login" action="login.php" method="POST" id="loginForm" novalidate="novalidate">
              <div class="control-group">
                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                  <label>E-Mail Adresse</label>
                  <input class="form-control" name="email" id="email" type="email" placeholder="E-Mail Adresse" required="required" data-validation-required-message="Bitte E-Mail Adresse eingeben.">
                  <p class="help-block text-danger"></p>
                </div>
              </div>
              <div class="control-group">
                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                  <label>Passwort</label>
                  <input class="form-control" name="passwort" id="password" type="password" placeholder="Passwort" required="required" data-validation-required-message="Bitte Passwort eingeben.">
                  <p class="help-block text-danger"></p>
                </div>
              </div>
              <br>
              <div id="success"></div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-xl" id="sendLoginButton">Anmelden</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

	<!-- Footer -->
    <div class="copyright py-4 text-center text-white">
      <div class="container">
        <small>Copyright &copy; Stopwatch 2019</small>
      </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->


    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="../js/contact_me.js"></script>

    <!-- Custom scripts for this template -->
    <script src="../js/freelancer.js"></script>

  </body>

</html>
