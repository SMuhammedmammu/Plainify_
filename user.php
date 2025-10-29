<?php
session_start();
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Escape user input to prevent SQL injection
$email = $conn->real_escape_string($_POST['email']);
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify the entered password against the stored hash
    if (password_verify($password, $user['password'])) {
        // Save session (use 'username' field from DB)
        $_SESSION['user'] = $user['username'];

        // Redirect to admin panel
        header("Location: index.php");
        exit();
    } else {
        echo "Incorrect password!";
    }
} else {
    echo "No user found with this email!";
}

$conn->close();
?>
