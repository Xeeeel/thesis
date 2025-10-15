<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("You need to log in to report a product.");
}

$product_id = $_POST['product_id'];
$buyer_id = $_POST['buyer_id'];
$report_reason = $_POST['report_reason'];
$other_description = isset($_POST['other_description']) ? $_POST['other_description'] : '';

// Validate input
if (empty($product_id) || empty($buyer_id) || empty($report_reason)) {
    die("Please fill out all required fields.");
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert report into the database
$report_sql = "INSERT INTO reports (product_id, buyer_id, report_reason, other_description, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($report_sql);
$stmt->bind_param("iiss", $product_id, $buyer_id, $report_reason, $other_description);
if ($stmt->execute()) {
    echo "Report submitted successfully!";
} else {
    echo "Error submitting report: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
