<?php
session_start();
include 'db.php'; // include DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password (assuming passwords are hashed)
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['email'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['success'] = "Login successful!";
            header("Location: user_dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password.";
        }
    } else {
        $_SESSION['error'] = "No account found with that email.";
    }

    header("Location: user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Login - Planify</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #0F172A;
      font-family: 'Inter', sans-serif;
      color: #E5E7EB;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    .login-box {
      background-color: #1E293B;
      padding: 30px;
      border-radius: 12px;
      width: 350px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    .error-msg {
      background: #ef4444;
      color: white;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      font-size: 14px;
    }
    .success-msg {
      background: #10b981;
      color: white;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      font-size: 14px;
    }
    input {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      border-radius: 8px;
      border: none;
      background-color: #334155;
      color: #fff;
      box-sizing: border-box;
    }
    button {
      width: 100%;
      padding: 12px;
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
    .signup-link {
      margin-top: 15px;
      display: block;
      color: #9CA3AF;
      text-decoration: none;
    }
    .signup-link:hover {
      color: #A78BFA;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="error-msg">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<div class="success-msg">' . htmlspecialchars($_SESSION['success']) . '</div>';
        unset($_SESSION['success']);
    }
    ?>
    
    <h2>Sign In</h2>
    <form action="user.php" method="POST" autocomplete="on">
      <input type="email" name="email" placeholder="Email" required autocomplete="email">
      <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
      <button type="submit">Login</button>
    </form>
    <a class="signup-link" href="signup.php">Don't have an account? Sign up</a>
  </div>
</body>
</html>