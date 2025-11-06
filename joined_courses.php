<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header("Location: user.php"); exit();
}
require_once 'db.php';

$current_email = $_SESSION['user'];

// Handle mark completed / quit actions (GET)
if (isset($_GET['action']) && isset($_GET['course_id'])) {
    $course_id = (int)$_GET['course_id'];
    $action = $_GET['action'];
    if (in_array($action, ['completed','quit'])) {
        $upd = $conn->prepare("UPDATE user_courses SET status = ? WHERE user_email = ? AND course_id = ?");
        $upd->bind_param("ssi", $action, $current_email, $course_id);
        $upd->execute();
        $upd->close();
    }
    header("Location: joined_courses.php"); exit();
}

// fetch joined courses
$stmt = $conn->prepare("
    SELECT c.*, uc.status, uc.id as uc_id 
    FROM courses c 
    INNER JOIN user_courses uc ON c.id = uc.course_id 
    WHERE uc.user_email = ? AND uc.status = 'joined'
");
$stmt->bind_param("s", $current_email);
$stmt->execute();
$joined = $stmt->get_result();
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>My Joined Courses</title></head>
<body>
<?php include 'sidebar.php'; ?>
<div id="page-content">
  <h1 style="color:#0a4f9a">My Joined Courses</h1>

  <?php if ($joined->num_rows > 0): ?>
    <?php while($row = $joined->fetch_assoc()): ?>
      <div class="card p-3 mb-3 d-flex justify-content-between align-items-center">
        <div>
          <strong><?php echo htmlspecialchars($row['course_name']); ?></strong><br>
          <small><?php echo htmlspecialchars($row['course_duration']); ?></small>
        </div>
        <div style="display:flex; gap:8px; align-items:center;">
          <div>₹<?php echo number_format((float)$row['course_fee'],2); ?></div>
          <a href="joined_courses.php?action=completed&course_id=<?php echo $row['id']; ?>"><button class="btn btn-success">Mark Completed</button></a>
          <a href="joined_courses.php?action=quit&course_id=<?php echo $row['id']; ?>"><button class="btn btn-danger">Quit</button></a>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="card p-3">You have not joined any active courses.</div>
  <?php endif; ?>

</div>
</body>
</html>
