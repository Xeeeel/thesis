<?php
// Start session
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'cartsy';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the required parameters are set
if (isset($_POST['product_id']) && isset($_POST['status'])) {
    $product_id = intval($_POST['product_id']);
    $status = $_POST['status'];

    // Ensure the status is either 'approved' or 'rejected'
    if (in_array($status, ['approved', 'rejected'])) {
        // Update the product status in the database
        $sql = "UPDATE products SET product_status = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $product_id);

        if ($stmt->execute()) {
            // Redirect to the product review page after successful update
            header("Location: product_review.php?id=" . $product_id);
            exit();
        } else {
            echo "Error updating product status: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Invalid status.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
