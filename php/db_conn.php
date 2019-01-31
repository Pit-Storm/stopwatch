 <?php
$servername = "localhost";
$username = "";
$password = "";
$db = "";

// Create connection
$db_conn = mysqli_connect($servername, $username, $password,$db);

// Check connection
if ($db_conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?> 