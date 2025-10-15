<?php
session_start();

// Ensure the user is logged in
if (isset($_SESSION['id'])) {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cartsy";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the seller's verification status from the database
    $user_id = $_SESSION['id'];
    $sql = "SELECT verification_status FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($verification_status);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    // Redirect based on verification status
    if ($verification_status === 'Approved') {
        // Redirect to the post-1-3.php page if approved
        header("Location: http://localhost/cartsy/seller/post-1-3.php");
        exit();
    } else {
        // Redirect to the identity-1.php page to fill up the verification form
        header("Location: http://localhost/cartsy/seller/identity-1.php");
        exit();
    }
} else {
    // Redirect to login page if the user is not logged in
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}
?>
