<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['user'];
$sql = "SELECT * FROM admins WHERE username='$username'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile - Planify Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Inter', sans-serif;
  background-color: #0F172A;
  color: #E5E7EB;
  margin: 0;
}
.profile-container {
  width: 400px;
  margin: 80px auto;
  background-color: #1E293B;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
.profile-container h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #A78BFA;
}
.profile-container img {
  display: block;
  margin: 0 auto 15px;
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
}
input[type="text"], input[type="email"], input[type="password"] {
  width: 100%;
  padding: 10px;
  margin: 8px 0;
  border-radius: 8px;
  border: none;
  background-color: #334155;
  color: #fff;
}
button {
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 8px;
  background-color: #A78BFA;
  color: #0F172A;
  font-weight: 600;
  cursor: pointer;
  margin-top: 10px;
}
button:hover {
  background-color: #C084FC;
}
a.back {
  display: block;
  text-align: center;
  color: #A78BFA;
  text-decoration: none;
  margin-top: 10px;
}
a.back:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="profile-container">
  <h2>Your Profile</h2>
  <!-- Use default.png if profile_pic is null -->
  <img src="uploads/<?php echo htmlspecialchars($user['profile_pic'] ?? 'default.png'); ?>" alt="Profile Picture">

  <form action="update_profile.php" method="POST" enctype="multipart/form-data">
    <label>Name</label>
    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

    <label>Change Password</label>
    <input type="password" name="password" placeholder="Leave blank to keep old password">

    <label>Profile Picture</label>
    <input type="file" name="profile_pic">

    <button type="submit">Update Profile</button>
  </form>
  <a href="adminpanel.php" class="back">← Back to Dashboard</a>
</div>

</body>
</html>
