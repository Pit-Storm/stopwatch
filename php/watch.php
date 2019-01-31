<?php
//Session eröffnen
session_start();
if(!array_key_exists('email',$_SESSION)){
	header('location: signin.php');
}
else{

### Zeit nehmen ###

# Prüfen, ob $_POST gesetzt ist
if(isset($_POST)){

# DB-Verbindung includieren
include 'db_conn.php';

# Prüfen, ob es sich um ein Projekt oder eine Tätigkeit handelt
if(array_key_exists('projekt',$_POST)){
	$sql_watch = 'INSERT INTO arbeitszeiten (freelancer_id, projekt_id, Zeitstempel_Anfang) VALUES ('.$_SESSION["id"].','.$_POST["projekt"].',CURRENT_TIMESTAMP)';
	# echo($sql_watch);
}
if(array_key_exists('taetigkeit',$_POST)){
	$sql_watch = 'UPDATE arbeitszeiten SET taetigkeits_ID='.$_POST["taetigkeit"].',Zeitstempel_Ende=CURRENT_TIMESTAMP WHERE ID='.$_SESSION["watch_ID"];
	mysqli_query($db_conn,$sql_watch);
	
	$sql_watch = 'UPDATE arbeitszeiten SET arbeitsdauer=DATEDIFF(Zeitstempel_Ende,Zeitstempel_Anfang) WHERE ID='.$_SESSION["watch_ID"];
}

# Datenbank-Updaten
$db_watch = mysqli_query($db_conn,$sql_watch);

# Weiterleitung an logged.php
header('location: logged.php');
}
else{
	header('location: logged.php');
}
}

?>