<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: users.html");
    exit();
}

$id = $_SESSION['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Handle image upload
if (!empty($_FILES['profile_pic']['name'])) {
    $file_name = basename($_FILES["profile_pic"]["name"]);
    $target_dir = "uploads/";
    $target_file = $target_dir . $file_name;
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);
    $update_pic = ", profile_pic='$file_name'";
} else {
    $update_pic = "";
}

// Handle password change
if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $update_pass = ", password='$hashed_password'";
} else {
    $update_pass = "";
}

$sql = "UPDATE admins SET username='$username', email='$email' $update_pass $update_pic WHERE id='$id'";
if ($conn->query($sql)) {
    $_SESSION['user'] = $username;
    header("Location: profile.php?success=1");
} else {
    echo "Error updating profile: " . $conn->error;
}
?>
