<?php
session_start();
session_destroy(); // Destroy session data
header("Location: http://localhost/cartsy/login/login.php"); // Redirect to login page
exit();
?>