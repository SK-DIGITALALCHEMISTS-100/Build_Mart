<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: \Build_Mart\User\Home.html");  // Redirect to login if not logged in
    exit();
}
?>
<h1>Welcome User</h1>
<a href="logout.php">Logout</a>
