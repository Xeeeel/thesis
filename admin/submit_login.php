<?php
// login.php

// Sample credentials (In real applications, you would check this from a database)
$valid_username = "admin";
$valid_password = "admin"; // Example password, never store plain passwords in real applications

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user inputs
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate credentials (this is just a basic check, you would typically do this with a database query)
    if ($username === $valid_username && $password === $valid_password) {
        // Redirect to admin.php upon successful login
        header("Location: admin.php");
        exit();
    } else {
        // If login failed, redirect back to the login page with an error message
        echo "<script>alert('Invalid username or password. Please try again.'); window.location.href='index.php';</script>";
    }
}
?>
