<?php
$host = "localhost";
$user = "root";  // your database username
$pass = "";  // your database password
$dbname = "build_mart";  // your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
