<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: courses.php");
    exit();
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM courses WHERE id = $id");
if ($result->num_rows === 0) {
    header("Location: courses.php");
    exit();
}
$course = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['course_name']);
    $fee = floatval($_POST['course_fee']);
    $duration = trim($_POST['course_duration']);
    $stmt = $conn->prepare("UPDATE courses SET course_name=?, course_fee=?, course_duration=? WHERE id=?");
    $stmt->bind_param("sdsi", $name, $fee, $duration, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: courses.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Course - Planify</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
  margin: 0;
  font-family: 'Inter', sans-serif;
  background-color: #0F172A;
  color: #E5E7EB;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
form {
  background-color: #1E293B;
  padding: 30px;
  border-radius: 10px;
  width: 100%;
  max-width: 400px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
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
a { color: #A78BFA; text-decoration: none; display: inline-block; margin-top: 10px; }
</style>
</head>
<body>
<form method="POST">
  <h2 style="color:#A78BFA;">Edit Course</h2>
  <input type="text" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
  <input type="number" name="course_fee" value="<?php echo htmlspecialchars($course['course_fee']); ?>" step="0.01" required>
  <input type="text" name="course_duration" value="<?php echo htmlspecialchars($course['course_duration']); ?>" required>
  <button type="submit">Update</button>
  <a href="courses.php">← Back</a>
</form>
</body>
</html>
