<?php
// user_dashboard.php
session_start();
require_once 'db.php'; // your DB connection file

// redirect if not logged in
if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header("Location: user.php");
    exit();
}

$current_id = (int)$_SESSION['user_id'];

// fetch user info
$stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $current_id);
$stmt->execute();
$userResult = $stmt->get_result();
$userData = $userResult->fetch_assoc();
$stmt->close();

if (!$userData) {
    session_unset();
    session_destroy();
    header("Location: user.php");
    exit();
}

// counts for cards (optional)
$cntStmt = $conn->prepare("
  SELECT
    SUM(uc.status = 'joined') AS joined_count,
    SUM(uc.status = 'completed') AS completed_count,
    SUM(uc.status = 'quit') AS quit_count
  FROM user_courses uc
  WHERE uc.user_email = ?
");
$cntStmt->bind_param("s", $userData['email']);
$cntStmt->execute();
$counts = $cntStmt->get_result()->fetch_assoc();
$cntStmt->close();

$joined_count = (int)($counts['joined_count'] ?? 0);
$completed_count = (int)($counts['completed_count'] ?? 0);
$quit_count = (int)($counts['quit_count'] ?? 0);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Planify - Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    /* small inline helpers for cards (you can move this to main CSS) */
    .card-quick { border-radius:10px; box-shadow:0 6px 16px rgba(16,24,40,0.06); }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<!-- Main content - IMPORTANT: id must be page-content so sidebar can push it -->
<main id="page-content">
  <div class="d-flex justify-content-between align-items-start mb-3">
    <div>
      <h2 style="color:#0a4f9a; margin-bottom:6px;">Welcome, <?php echo htmlspecialchars($userData['name']); ?></h2>
      <div style="color:#334155;">Email: <strong><?php echo htmlspecialchars($userData['email']); ?></strong></div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-sm-4">
      <div class="card card-quick p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted">Joined</small>
            <div style="font-size:1.4rem; font-weight:700;"><?php echo $joined_count; ?></div>
          </div>
          <div><i class="fas fa-user-check fa-2x" style="color:#3f8edc;"></i></div>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card card-quick p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted">Completed</small>
            <div style="font-size:1.4rem; font-weight:700;"><?php echo $completed_count; ?></div>
          </div>
          <div><i class="fas fa-trophy fa-2x" style="color:#10b981;"></i></div>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card card-quick p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted">Quit</small>
            <div style="font-size:1.4rem; font-weight:700;"><?php echo $quit_count; ?></div>
          </div>
          <div><i class="fas fa-times-circle fa-2x" style="color:#ef4444;"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="card p-3">
    <h5>Quick Actions</h5>
    <p>Use the left menu to navigate. Click <strong>Settings</strong> to edit your profile.</p>
    <div class="mt-3 d-flex gap-2">
      <a class="btn btn-primary" href="available_courses.php">Browse Courses</a>
      <a class="btn btn-success" href="joined_courses.php">My Joined Courses</a>
      <a class="btn btn-outline-secondary" href="settings.php">Settings</a>
    </div>
  </div>

</main>

</body>
</html>
