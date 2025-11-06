<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header("Location: user.php");
    exit();
}
require_once 'db.php';

$current_id = (int)$_SESSION['user_id'];
$profileMsg = '';
$profileErr = '';

// Fetch user data
$stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $current_id);
$stmt->execute();
$res = $stmt->get_result();
$userData = $res->fetch_assoc();
$stmt->close();

if (!$userData) {
    session_unset(); session_destroy();
    header("Location: user.php"); exit();
}

// Handle profile update from modal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $new_password = trim($_POST['password'] ?? '');

    if ($new_name === '') {
        $profileErr = "Name cannot be empty.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $profileErr = "Please enter a valid email address.";
    } else {
        if ($new_email !== $userData['email']) {
            $chk = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $chk->bind_param("si", $new_email, $current_id);
            $chk->execute();
            $chk->store_result();
            if ($chk->num_rows > 0) {
                $profileErr = "That email is already registered by another account.";
            }
            $chk->close();
        }
    }

    if ($profileErr === '') {
        if ($new_password !== '') {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $upd = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $upd->bind_param("sssi", $new_name, $new_email, $password_hash, $current_id);
        } else {
            $upd = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $upd->bind_param("ssi", $new_name, $new_email, $current_id);
        }

        if ($upd->execute()) {
            $upd->close();
            // Update user_courses email if changed
            if ($new_email !== $userData['email']) {
                $upc = $conn->prepare("UPDATE user_courses SET user_email = ? WHERE user_email = ?");
                $upc->bind_param("ss", $new_email, $userData['email']);
                $upc->execute();
                $upc->close();
            }
            $_SESSION['user'] = $new_email;
            $userData['name'] = $new_name;
            $userData['email'] = $new_email;
            $profileMsg = "Profile updated successfully.";
        } else {
            $profileErr = "Failed to update profile. Please try again.";
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Settings - Planify</title>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div id="page-content">
  <h1 style="color:#0a4f9a">Settings</h1>

  <?php if ($profileMsg): ?><div class="notice-success"><?php echo htmlspecialchars($profileMsg); ?></div><?php endif; ?>
  <?php if ($profileErr): ?><div class="notice-err"><?php echo htmlspecialchars($profileErr); ?></div><?php endif; ?>

  <div class="card p-3" style="max-width:800px">
    <h4>My Profile</h4>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($userData['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
  </div>

  <!-- Edit Profile Modal -->
  <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post">
          <div class="modal-header">
            <h5 class="modal-title">Edit Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input name="name" type="text" class="form-control" required value="<?php echo htmlspecialchars($userData['name']); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input name="email" type="email" class="form-control" required value="<?php echo htmlspecialchars($userData['email']); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">New Password <small>(optional)</small></label>
              <input name="password" type="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="update_profile" class="btn btn-success">Save changes</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
</body>
</html>
