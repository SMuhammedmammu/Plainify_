<?php
// db.php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "planify_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ensure proper charset
$conn->set_charset('utf8mb4');
?>

