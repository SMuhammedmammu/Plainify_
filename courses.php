<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}
include 'db.php';

// Handle adding a new course
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_course'])) {
    $name = trim($_POST['course_name']);
    $fee = floatval($_POST['course_fee']);
    $duration = trim($_POST['course_duration']);
    if ($name !== "" && $duration !== "") {
        $stmt = $conn->prepare("INSERT INTO courses (course_name, course_fee, course_duration) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $name, $fee, $duration);
        $stmt->execute();
        $stmt->close();
        header("Location: courses.php");
        exit();
    }
}

// Fetch all courses
$result = $conn->query("SELECT * FROM courses ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Courses - Planify Admin</title>
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
h2 {
  color: #A78BFA;
  font-weight: 600;
}
table {
  width: 100%;
  border-collapse: collapse;
  background-color: #1E293B;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  margin-top: 20px;
}
th, td {
  padding: 12px 16px;
  text-align: left;
}
th {
  background-color: #334155;
  color: #A78BFA;
  text-transform: uppercase;
  font-size: 14px;
}
td {
  border-top: 1px solid #334155;
}
tr:hover {
  background-color: #273449;
}
.action-btn {
  text-decoration: none;
  padding: 6px 10px;
  border-radius: 6px;
  font-weight: 600;
  transition: background 0.3s ease;
  margin-right: 6px;
}
.edit-btn { background-color: #6366F1; color: white; }
.edit-btn:hover { background-color: #4F46E5; }
.delete-btn { background-color: #EF4444; color: white; }
.delete-btn:hover { background-color: #DC2626; }

form {
  background-color: #1E293B;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  width: 100%;
  max-width: 400px;
}
input[type="text"], input[type="number"] {
  width: 100%;
  padding: 10px;
  margin: 8px 0;
  border-radius: 6px;
  border: none;
  background-color: #334155;
  color: #E5E7EB;
}
button {
  background-color: #A78BFA;
  border: none;
  padding: 10px 16px;
  border-radius: 6px;
  font-weight: 600;
  color: #0F172A;
  cursor: pointer;
  transition: background 0.3s ease;
}
button:hover { background-color: #C4B5FD; }
</style>
<script>
function confirmDelete(name) {
  return confirm("Are you sure you want to delete the course: " + name + "?");
}
</script>
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
  <h2>Manage Courses</h2>

  <form method="POST" action="">
    <h3 style="color:#A78BFA;">Add New Course</h3>
    <input type="text" name="course_name" placeholder="Course Name" required>
    <input type="number" name="course_fee" placeholder="Course Fee" step="0.01" required>
    <input type="text" name="course_duration" placeholder="Course Duration (e.g. 6 Months)" required>
    <button type="submit" name="add_course">Add Course</button>
  </form>

  <table>
    <tr><th>ID</th><th>Course Name</th><th>Fee</th><th>Duration</th><th>Actions</th></tr>
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>" . htmlspecialchars($row['course_name']) . "</td>
                    <td>₹" . number_format($row['course_fee'], 2) . "</td>
                    <td>" . htmlspecialchars($row['course_duration']) . "</td>
                    <td>
                      <a href='edit_course.php?id={$row['id']}' class='action-btn edit-btn'>Edit</a>
                      <a href='delete_course.php?id={$row['id']}' class='action-btn delete-btn' onclick='return confirmDelete(\"" . htmlspecialchars($row['course_name']) . "\")'>Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5' style='text-align:center;'>No courses found.</td></tr>";
    }
    ?>
  </table>
   <a href="adminpanel.php" class="back">← Back to Dashboard</a>  
</div>
</body>
</html>
