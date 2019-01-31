<?php
//Session eröffnen
session_start();
if(!array_key_exists('email',$_SESSION)){
	header('location: signin.php');
}
else{
	// DB-Verbindung
	include 'db_conn.php';
	
	// Fetch Profil-Data from Table 'freelancer'
	$sql_freelancer = 'SELECT ID,Email, Name,Vorname,Adresszeile,PLZ,Stadt,IBAN,BIC FROM freelancer WHERE ID='.$_SESSION["id"];
	$db_freelancer = mysqli_query($db_conn, $sql_freelancer);
	
	// Fetch Kunden-Daten from Table 'kunden'
	$sql_kunden = 'SELECT DISTINCT ku.ID, ku.Firmenname,ku.Adresszeile,ku.PLZ,ku.Stadt,ku.Ansprechpartner,ku.Email,ku.Stundensatz FROM projekte pr,kunden ku WHERE ku.ID=pr.kunden_id AND pr.freelancer_id='.$_SESSION["id"].' ORDER BY ku.Firmenname ASC';
	$db_kunden = mysqli_query($db_conn, $sql_kunden);
	
	// Fetch Projekte from table 'projekte'
	$sql_projekte = 'SELECT pr.ID, pr.Titel, pr.Umfang, ku.Firmenname, ta.Taetigkeit FROM projekte pr, kunden ku, taetigkeiten ta WHERE pr.kunden_id = ku.ID AND pr.ID=ta.projekt_id AND pr.freelancer_ID ='.$_SESSION["id"].' ORDER BY pr.Titel ASC';
	$db_projekte = mysqli_query($db_conn, $sql_projekte);
	$erg_projekte = array();
	while($zeile = mysqli_fetch_assoc($db_projekte)){
		$erg_projekte[] = $zeile;
	};
	
	// Fetch Arbeitszeiten from table 'arbeitszeiten'
	$sql_arbeitszeiten = 'SELECT ar.ID, pr.Titel, ta.Taetigkeit, ar.Zeitstempel_Anfang, ar.Zeitstempel_Ende FROM projekte pr, taetigkeiten ta, arbeitszeiten ar WHERE ar.projekt_id = pr.ID AND ar.taetigkeits_id=ta.ID AND ar.freelancer_id='.$_SESSION["id"].' ORDER BY Zeitstempel_Anfang ASC';
	$db_arbeitszeiten = mysqli_query($db_conn, $sql_arbeitszeiten);
	
	// Fetch Last-Arbeitszeit from table 'arbeitszeiten'
	 $sql_letzte_zeit = 'SELECT ID, projekt_ID, Zeitstempel_Anfang, Zeitstempel_Ende FROM arbeitszeiten WHERE freelancer_ID='.$_SESSION["id"].' ORDER BY id DESC LIMIT 1';
	$db_letzte_zeit = mysqli_query($db_conn, $sql_letzte_zeit);
	$erg_letzte_zeit = array();
	if($db_letzte_zeit != ""){
		$erg_letzte_zeit = mysqli_fetch_assoc($db_letzte_zeit);
		$_SESSION["watch_ID"] = $erg_letzte_zeit["ID"];
	}
	else{
		$erg_letzte_zeit["Zeitstempel_Anfang"]=0;
		$erg_letzte_zeit["Zeitstempel_ende"]=0;
	}
	
	// Projekte für die Watch laden
	$sql_watch_proj = 'SELECT DISTINCT pr.ID, pr.Titel, ku.Firmenname FROM projekte pr, kunden ku WHERE pr.kunden_id = ku.ID AND pr.freelancer_ID ='.$_SESSION["id"].' ORDER BY Firmenname ASC, Titel ASC';
	$db_watch_proj = mysqli_query($db_conn,$sql_watch_proj);
	$erg_watch_proj = array();
	while($watch_proj_zeile = mysqli_fetch_assoc($db_watch_proj)){
		$erg_watch_proj[] = $watch_proj_zeile;
	};
	
	// Tätigkeitsliste der aktuellen Watch laden
	$sql_watch_taet = 'SELECT ID,taetigkeit FROM taetigkeiten WHERE projekt_ID='.$erg_letzte_zeit["projekt_ID"].' ORDER BY taetigkeit ASC';
	$db_watch_taet = mysqli_query($db_conn,$sql_watch_taet);
	
	// Fetch Rechnungen from table 'rechnungen'
	// $sql_rechnungen = "";
	// $db_rechnungen = mysqli_query($db_conn, $sql_rechnungen);
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Stopwatch - Freelancer Time-Management</title>

    <!-- Bootstrap core CSS -->
    <link href="././vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="././vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

	<!-- Plugin CSS -->
    <link href="././vendor/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="././vendor/data-tables/css/dataTables.bootstrap4.css"/>
	<link rel="stylesheet" type="text/css" href="././vendor/dt-responsive/css/responsive.bootstrap4.css"/>
	<link rel="stylesheet" type="text/css" href="././vendor/dt-row-group/css/rowGroup.bootstrap4.css"/>
	
    <!-- Custom styles for this template -->
    <link href="././css/freelancer.css" rel="stylesheet">

  </head>

  <body id="page-top">
  
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg bg-secondary fixed-top text-uppercase" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top"><i class="fas fa-clock"></i></a>
        <button class="navbar-toggler navbar-toggler-right text-uppercase bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars"></i>
		  Menü
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#arbeitszeit">Arbeitszeiten</a>
            </li>
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#projekte">Projekte</a>
			</li>
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#kunden">Kunden</a>
            </li>
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#rechnungen">Rechnungen</a>
            </li>
			<li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <nobr><i class="fas fa-user-circle fa-fw"></i>Hi, <?php echo $_SESSION["vorname"] ?></nobr>
          </a>
          <div class="dropdown-menu dropdown-menu-right bg-secondary" aria-labelledby="userDropdown">
            <a class="dropdown-item nav-link py-3 px-0 px-lg-3 rounded" href="#profil">Mein Profil</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item nav-link py-3 px-0 px-lg-3 rounded" href="logout.php" >Abmelden</a>
          </div>
</li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Header -->
    <header class="masthead bg-primary text-white text-center">
      <div class="container">
        <h1 class="text-uppercase mb-0">Zeit erfassen</h1>
		<hr class="star-light mb-5">
		<?php
		### Abfrage, welches Watch-Formular angezeigt wird
		
		# Abfrage Anfang für Start-Formular
		if($erg_letzte_zeit == "" OR $erg_letzte_zeit["Zeitstempel_Ende"]!="0000-00-00 00:00:00"){
		?>
		<form name="watch_start" id="watch_start" method="post" action="watch.php">
              <div class="control-group row justify-content-center">
			  <div class="form-group col-md-auto">
                  <label for="projekt">Projekt</label>
                  <select class="form-control form-control-watch"  id="projekt" name="projekt">
				  <?php
				  	$i = 0;
	foreach($erg_watch_proj AS $tupel){
		if($erg_watch_proj[$i]["Firmenname"] != $erg_watch_proj[$i-1]["Firmenname"]){
				  echo('<optgroup label="'.$tupel["Firmenname"].'">');
		}
		echo('<option value="'.$tupel["ID"].'">'.$tupel["Titel"].'</option>');
		if($erg_watch_proj[$i]["Firmenname"] != $erg_watch_proj[$i+1]["Firmenname"]){
					echo('</optgroup>');
		}
		$i++;
	}
					?>
				</select>
              </div>
              <div class="form-group col-md-auto">
                <button type="submit" class="btn btn-secondary btn-xl" id="clock_btn_start">Start</button>
              </div>
			  </div>
            </form>
			<?php
		}
			# Abfrage Ende für Start-Formular
			
			# Abfrage Anfang für Stop-Formular
			if($erg_letzte_zeit["Zeitstempel_Ende"]=="0000-00-00 00:00:00"){
			?>
			<form name="watch_stop" id="watch_stop" method="post" action="watch.php">
              <div class="control-group row justify-content-center">
				<div class="form-group col-md-3">
                  <label for="taetigkeit">Tätigkeit</label>
                  <select class="form-control form-control-watch" id="taetigkeit" name="taetigkeit">
				  <?php
				  while($erg_watch_taet = mysqli_fetch_assoc($db_watch_taet)){
					echo('<option value="'.$erg_watch_taet["ID"].'">'.$erg_watch_taet["taetigkeit"].'</option>');
				  }
				  ?>
				</select>
              </div>
			  <div class="form-group col-md-2">
                <button type="submit" class="btn btn-secondary btn-xl" id="clock_btn_stop">Stop</button>
              </div>
			  </div>
            </form>
			<?php
			}
			# Abfrage Ende für Stop-Formular
			?>
      </div>
    </header>

    <!-- Arbeitszeit Section -->
    <section class="" id="arbeitszeit">
      <div class="container">
        <h2 class="text-center text-uppercase text-secondary mb-0">Meine Arbeitszeiten</h2>
        <hr class="star-dark mb-5">
        <div class="row justify-content-center">
          <table id="arbeitszeiten-table" class="data table">
    <thead>
        <tr>
            <th>Projekt</th>
            <th>Tätigkeit</th>
			<th>Gestartet</th>
			<th>Gestoppt</th>
			<th>Dauer</th>
        </tr>
    </thead>
    <tbody>
	<?php
	while($zeile_arbeitszeiten = mysqli_fetch_assoc($db_arbeitszeiten)){
        echo('<tr>
            <td>'.$zeile_arbeitszeiten["Titel"].'</td>
            <td>'.$zeile_arbeitszeiten["Taetigkeit"].'</td>
			<td>'.$zeile_arbeitszeiten["Zeitstempel_Anfang"].'</td>
			<td>'.$zeile_arbeitszeiten["Zeitstempel_Ende"].'</td>');
			$start_date = new DateTime($zeile_arbeitszeiten["Zeitstempel_Anfang"]);
$dauer = $start_date->diff(new DateTime($zeile_arbeitszeiten["Zeitstempel_Ende"]));
			echo('<td>'.$dauer->format('%H').':'.$dauer->format('%I').'</td>
        </tr>');
	};?>
    </tbody>
</table>
          
        </div>
      </div>
    </section>

    <!-- Projekte Section -->
    <section class="bg-primary text-white mb-0" id="projekte">
      <div class="container">
        <h2 class="text-center text-uppercase text-white">Meine Projekte</h2>
        <hr class="star-light mb-5">
        <div class="row justify-content-center">
		  <table id="projekte-table" class="data table">
    <thead>
        <tr>
            <th>Tätigkeit</th>
            <th>Projekt</th>
			<th>Umfang Std.</th>
			<th>Kunde</th>
        </tr>
    </thead>
    <tbody>
	<?php foreach($erg_projekte AS $zeile_projekte_table){
        echo('<tr>
            <td>'.$zeile_projekte_table["Taetigkeit"].'</td>
            <td>'.$zeile_projekte_table["Titel"].'</td>
			<td>'.$zeile_projekte_table["Umfang"].'</td>
			<td>'.$zeile_projekte_table["Firmenname"].'</td>
        </tr>');
	}?>
    </tbody>
</table>
        </div>
      </div>
    </section>

    <!-- Kunden Section -->
    <section class="" id="kunden">
      <div class="container">
        <h2 class="text-center text-uppercase text-secondary mb-0">Meine Kunden</h2>
        <hr class="star-dark mb-5">
        <div class="row justify-content-center">
		<table id="kunden-table" class="data table">
			<thead>
				<tr>
					<th>Firmenname</th>
					<th>Adresse</th>
					<th>PLZ</th>
					<th>Ort</th>
					<th>Ansprechpartner</th>
					<th>E-Mail</th>
					<th>Stundensatz</th>
				</tr>
			</thead>
			<tbody>
				  <?php while($kunden = mysqli_fetch_array( $db_kunden, MYSQL_ASSOC)){
  echo('<tr>
			<td>'.$kunden["Firmenname"].'</td>
			<td>'.$kunden["Adresszeile"].'</td>
			<td>'.$kunden["PLZ"].'</td>
			<td>'.$kunden["Stadt"].'</td>
			<td>'.$kunden["Ansprechpartner"].'</td>
			<td>'.$kunden["Email"].'</td>
			<td>'.$kunden["Stundensatz"].'</td>
		</tr>');}?>
	</tbody>
	</table>
        </div>
      </div>
    </section>

    <!-- Rechnungen Section -->
    <section class="bg-primary text-white mb-0" id="rechnungen">
      <div class="container">
        <h2 class="text-center text-uppercase text-white">Meine Rechnungen</h2>
        <hr class="star-light mb-5">
        <div class="row justify-content-center">
		  <table id="rechnungen-table" class="data table">
    <thead>
        <tr>
            <th>Kunde</th>
            <th>Projekt</th>
			<th>Arbeitszeit</th>
			<th>€/Std.</th>
			<th>Rechn. Betrag</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
        </div>
      </div>
    </section>

	<!-- Profil Section -->
    <section class="" id="profil">
      <div class="container">
        <h2 class="text-center text-uppercase text-secondary mb-0">Mein Profil</h2>
        <hr class="star-dark mb-5">
        <div class="row justify-content-center" id="profil">
		   <a class="btn btn-primary btn-lg rounded-pill edit-userdata-button" href="#edit-userdata-container">
                <i class="fa fa-edit"></i>
                Bearbeiten</a>
				</div>
		<div class="row justify-content-center">
		<div class="col-md-auto">
			<table id="profil-profil" class="table">
    <thead>
        <tr>
            <th>Profildaten</th>
        </tr>
    </thead>
    <tbody>
	<?php $freelancer = mysqli_fetch_array( $db_freelancer, MYSQL_ASSOC);
        echo('
		<tr>
			<td>'.$freelancer['Email'].'</td>
		</tr>
		<tr>
			<td>********</td>
		</tr>
	</tbody>
	</table>
	</div>
		<div class="col-md-auto">
			<table id="profil-pers" class="table">
    <thead>
        <tr>
            <th>Adressdaten</th>
        </tr>
    </thead>
    <tbody>
		<tr>
            <td>'.$freelancer["Vorname"].' '.$freelancer["Name"].'</td>
        </tr>
		<tr>
            <td>'.$freelancer["Adresszeile"].'</td>
        </tr>
		<tr>
            <td>'.$freelancer["PLZ"].' '.$freelancer["Stadt"].'</td>
        </tr>
    </tbody>
</table>
</div>
<div class="col-md-auto">
<table id="profil-bank" class="table">
	<thead>
		<tr>
			<th>Bankverbindung</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>'.$freelancer["IBAN"].'</td>
		</tr>
		<tr>
			<td>'.$freelancer["BIC"].'</td>
		</tr>'); ?>
	</tbody>
</table>
</div>
</div>
        </div>
    </section>
	

    <div class="copyright py-4 text-center text-white">
      <div class="container">
        <small>Copyright &copy; Stopwatch 2019</small>
      </div>
    </div>
	
	<!-- Edit-UserData Popup -->
    <div class="edit-userdata mfp-hide" id="edit-userdata-container">
      <div class="edit-userdata-dialog bg-white">
        <div class="container text-center">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <br /><h2 class="text-secondary text-uppercase mb-0">Benutzerdaten bearbeiten</h2>
              <p class="mb-5">Hier muss das Formular hin</p>
              <a class="btn btn-primary btn-lg rounded-pill edit-userdata-dismiss" href="#">
                <i class="fa fa-close"></i>
                Schließen</a><br /><br />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="././vendor/jquery/jquery.min.js"></script>
    <script src="././vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script type="text/javascript" src="././vendor/jquery-easing/jquery.easing.min.js"></script>
	<script type="text/javascript" src="././vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
	<script type="text/javascript" src="././vendor/data-tables/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="././vendor/data-tables/js/dataTables.bootstrap4.js"></script>
	<script type="text/javascript" src="././vendor/dt-responsive/js/dataTables.responsive.js"></script>
	<script type="text/javascript" src="././vendor/dt-row-group/js/dataTables.rowGroup.js"></script>

    <!-- Custom scripts for this template -->
    <script type="text/javascript" src="././js/freelancer.js"></script>
	<script type="text/javascript" src="././js/stopwatch.js"></script>
	
  </body>

</html>

<?php
}
?>