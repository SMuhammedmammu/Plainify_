<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare and execute delete query securely
    $stmt = $conn->prepare("DELETE FROM contact_requests WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Success — redirect back
        header("Location: contact_requests.php?msg=deleted");
        exit();
    } else {
        echo "<script>alert('Error deleting record.'); window.location.href='contact_requests.php';</script>";
    }

    $stmt->close();
} else {
    header("Location: contact_requests.php");
    exit();
}
?>
