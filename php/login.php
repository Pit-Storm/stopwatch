<?php
// Session starten
// Info: https://www.php-einfach.de/php-tutorial/php-sessions/
session_start();

// Variablen
$url_login = "http://bob.bwl.uni-mainz.de/ebis3/php/signin.php";
$url_logged = "http://bob.bwl.uni-mainz.de/ebis3/php/logged.php";

// E-Mail Adresse und Passwort einlesen von Formular
$email_form = $_POST["email"];
$pass_form = $_POST["passwort"];

if($email_form=="" OR $pass_form==""){
	$_SESSION["err"] = 3;
	header('location: '.$url_login);
	die();
}

// #TODO Passwort hashen
// Info: https://secure.php.net/manual/de/faq.passwords.php
// $passhash = password_hash($password, PASSWORD_BCRYPT);
// $password = NULL;

//DB Verbindung
include 'db_conn.php';

// SQL-Befehl zum abrufen von ID, E-Mail und Passwort
$sql = "SELECT ID,Email,Vorname,Passwort FROM freelancer WHERE Email='".$email_form."'";
$db_ergebnis = mysqli_query($db_conn, $sql);
$row = mysqli_fetch_array( $db_ergebnis, MYSQL_ASSOC);

// Wenn die Mailadresse nicht vorhanden ist einen Fehler zurückgeben
// Fehler in Sessionvariable schreiben.
if($row["Email"]!=$email_form){
	$_SESSION["err"] = 1;
	header('location: '.$url_login);
	die();
}

// Wenn die Mailadresse vorhanden ist, mit dem eingebenen Passwort verlgeichen.
// Wenn Passwort falsch, fehler zurückgeben
// --> Fehler in Session Variiable schreiben
if($row["Passwort"]!=$pass_form){
	$_SESSION["err"] = 2;
	header('location: '.$url_login);
	die();
}
// Wenn Passwort korrekt, Erfolg zurückgeben.
// E-Mail Adresse in Session Variable schreiben.
else{
	$_SESSION["email"] = $row["Email"];
	$_SESSION["id"] = $row["ID"];
$_SESSION['vorname'] = $row["Vorname"];
	header('location: '.$url_logged);
	die();
}
die();
?>