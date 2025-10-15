<?php
$conn = new mysqli('localhost', 'root', '', 'cartsy');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    echo "Input Username: " . $inputUsername . "<br>";  // Debugging line
    echo "Input Password: " . $inputPassword . "<br>";  // Debugging line

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        echo "Database Username: " . $user['username'] . "<br>";  // Debugging line
        echo "Database Password Hash: " . $user['password'] . "<br>";  // Debugging line

        // Verify the password
        if (password_verify($inputPassword, $user['password'])) {
            echo "Password verification successful! Redirecting...";
            header("Location: /sign-up/sign-up.php");
            exit();
        } else {
            echo "Password verification failed!";
            $error = "Invalid username or password.";
        }
    } else {
        echo "User not found!";
        $error = "Invalid username or password.";
    }
}
?>
