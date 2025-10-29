<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}
include 'db.php';

// Fetch all contact requests
$sql = "SELECT id, name, email, message, submitted_at FROM contact_requests ORDER BY submitted_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Requests - Planify Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
  margin: 0;
  font-family: 'Inter', sans-serif;
  background-color: #0F172A;
  color: #E5E7EB;
}

/* Sidebar */
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

/* Main Section */
.main {
  margin-left: 240px;
  padding: 20px;
}
h2 {
  color: #A78BFA;
  font-weight: 600;
  margin-bottom: 20px;
}

/* Table Styling */
table {
  width: 100%;
  border-collapse: collapse;
  background-color: #1E293B;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
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

/* Action Buttons */
.action-btn {
  text-decoration: none;
  padding: 6px 10px;
  border-radius: 6px;
  font-weight: 600;
  transition: background 0.3s ease;
  margin-right: 6px;
}
.edit-btn {
  background-color: #6366F1;
  color: white;
}
.edit-btn:hover {
  background-color: #4F46E5;
}
.delete-btn {
  background-color: #EF4444;
  color: white;
}
.delete-btn:hover {
  background-color: #DC2626;
}

/* Back Button */
.back {
  display: inline-block;
  margin-top: 20px;
  color: #A78BFA;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}
.back:hover {
  color: #C4B5FD;
}
</style>

<script>
function confirmDelete(name) {
  return confirm("Are you sure you want to delete the contact request from " + name + "?");
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
  <h2>Contact Requests</h2>
  <table>
    <tr><th>Name</th><th>Email</th><th>Message</th><th>Submitted At</th><th>Actions</th></tr>
    <?php
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['message']) . "</td>
                    <td>" . $row['submitted_at'] . "</td>
                    <td>
                      <a href='delete_contact.php?id=" . $row['id'] . "' class='action-btn delete-btn' onclick='return confirmDelete(\"" . htmlspecialchars($row['name']) . "\")'>Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5' style='text-align:center;'>No contact requests found.</td></tr>";
    }
    ?>
  </table>
  <a href="adminpanel.php" class="back">← Back to Dashboard</a>
</div>

</body>
</html>
