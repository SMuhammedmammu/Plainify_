<?php
// contact.php
include 'db.php';  // Include database connection

// Check form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Insert into database using a prepared statement for safety
    $stmt = $conn->prepare("INSERT INTO contact_requests (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    if ($stmt->execute()) {
        // Success: you could redirect back or show a message
        echo "Your message has been sent successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>
