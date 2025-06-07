<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: \Build_Mart\Admin\admin_home.html");  // Redirect to login if not logged in
    exit();
}
?>
<h1>Welcome Admin</h1>
<a href="logout.php">Logout</a>
