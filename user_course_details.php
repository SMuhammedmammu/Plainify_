<?php
session_start();
include 'db.php';

// Restrict to admin only (optional check)
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

// Fetch user-course relationships
$query = "
SELECT 
    u.email AS user_email, 
    c.course_name AS course_name, 
    uc.status
FROM user_courses uc
JOIN users u ON uc.user_email = u.email
JOIN courses c ON uc.course_id = c.id
ORDER BY u.email, c.course_name
";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>User Course Details - Planify</title>
<link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap' rel='stylesheet'>
<style>
body {
  font-family: 'Inter', sans-serif;
  background-color: #0F172A;
  color: #E5E7EB;
  margin: 0;
  padding: 20px;
}
h1 {
  color: #A78BFA;
  text-align: center;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 30px;
  background-color: #1E293B;
  border-radius: 10px;
  overflow: hidden;
}
th, td {
  padding: 12px 15px;
  text-align: left;
}
th {
  background-color: #334155;
  color: #A78BFA;
}
tr:nth-child(even) {
  background-color: #111827;
}
tr:hover {
  background-color: #374151;
}
.status-joined {
  color: #38BDF8;
  font-weight: 600;
}
.status-completed {
  color: #22C55E;
  font-weight: 600;
}
.status-quit {
  color: #EF4444;
  font-weight: 600;
}
.back-btn {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 18px;
  background-color: #A78BFA;
  color: #0F172A;
  border: none;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
}
.back-btn:hover {
  background-color: #C4B5FD;
}
</style>
</head>
<body>

<h1>User Course Details</h1>

<table>
  <tr>
    <th>User Email</th>
    <th>Course Name</th>
    <th>Status</th>
  </tr>
  <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
              <td><?php echo htmlspecialchars($row['user_email']); ?></td>
              <td><?php echo htmlspecialchars($row['course_name']); ?></td>
              <td class="status-<?php echo htmlspecialchars($row['status']); ?>">
                  <?php echo ucfirst($row['status']); ?>
              </td>
          </tr>
      <?php endwhile; ?>
  <?php else: ?>
      <tr>
          <td colspan="3" style="text-align:center;">No records found.</td>
      </tr>
  <?php endif; ?>
</table>

<a href="adminpanel.php" class="back-btn">← Back to Admin Dashboard</a>

</body>
</html>
