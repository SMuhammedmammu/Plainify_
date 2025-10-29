<?php
session_start();

// If not logged in, redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

// Include DB connection for stats
include 'db.php';

// Initialize stats
$totalContacts = 0;
$totalCourses = 0;

// Check if contact_requests table exists before querying
$tableCheck1 = $conn->query("SHOW TABLES LIKE 'contact_requests'");
if ($tableCheck1 && $tableCheck1->num_rows > 0) {
    $result1 = $conn->query("SELECT COUNT(*) AS total FROM contact_requests");
    if ($result1 && $row = $result1->fetch_assoc()) {
        $totalContacts = $row['total'];
    }
}

// Check if courses table exists before querying
$tableCheck2 = $conn->query("SHOW TABLES LIKE 'courses'");
if ($tableCheck2 && $tableCheck2->num_rows > 0) {
    $result2 = $conn->query("SELECT COUNT(*) AS total FROM courses");
    if ($result2 && $row = $result2->fetch_assoc()) {
        $totalCourses = $row['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Planify</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
  margin: 0;
  font-family: 'Inter', sans-serif;
  background-color: #0F172A;
  color: #E5E7EB;
}
.sidebar {
  position: fixed;
  width: 150px;
  height: 100%;
  background-color: #1E293B;
  display: flex;
  flex-direction: column;
  padding: 20px;
}
.sidebar a {
  color: #E5E7EB;
  text-decoration: none;
  padding: 10px 0;
  display: block;
  font-weight: 600;
  transition: color 0.3s ease;
}
.sidebar a:hover {
  color: #A78BFA;
}
.main {
  margin-left: 240px;
  padding: 20px;
}
.card {
  background-color: #1E293B;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 20px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  transition: transform 0.2s ease;
}
.card:hover {
  transform: translateY(-3px);
}
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}
.chart {
  background-color: #1E293B;
  border-radius: 12px;
  padding: 20px;
  margin-top: 20px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}
.chart h2 {
  margin-bottom: 10px;
  color: #A78BFA;
}
.chart canvas {
  width: 100%;
  max-height: 300px;
}
</style>
</head>
<body>

<div class="sidebar">
  <div>
    <a href="profile.php">Profile</a>
    <a href="contact_requests.php">Contact Requests</a>
    <a href="courses.php">Courses</a>
  </div>
  <div style="margin-top:590px;">
    <a href="logout.php">Logout</a>
  </div>
</div>

<div class="main">
  <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>

  <div class="grid">
    <div class="card">
      <h3>Profile Details</h3>
      <p>Manage your admin information, update credentials, and view your activity logs.</p>
      <a href="profile.php" style="color:#A78BFA;text-decoration:none;font-weight:600;">Edit Profile →</a>
    </div>

    <div class="card">
      <h3>Contact Requests</h3>
      <p>Total Requests: <strong><?php echo $totalContacts; ?></strong></p>
      <!-- In adminpanel.php -->
<a href="contact_requests.php" style="color:#A78BFA; text-decoration:none; font-weight:600;">
  View Requests →
</a>

    </div>

    <div class="card">
      <h3>Courses Overview</h3>
      <p>Total Courses: <strong><?php echo $totalCourses; ?></strong></p>
      <a href="courses.php" style="color:#A78BFA;text-decoration:none;font-weight:600;">Manage Courses →</a>
    </div>
  </div>
</div>

</body>
</html>
