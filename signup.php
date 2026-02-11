<?php
session_start();
require_once 'db.php';
require_once 'includes/auth.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
  header("Location: dashboard.php");
  exit();
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';

  // Validation
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $message = 'All fields are required';
    $message_type = 'error';
  } elseif (strlen($password) < 6) {
    $message = 'Password must be at least 6 characters long';
    $message_type = 'error';
  } elseif ($password !== $confirm_password) {
    $message = 'Passwords do not match';
    $message_type = 'error';
  } else {
    $result = registerUser($username, $email, $password);
    $message = $result['message'];
    $message_type = $result['success'] ? 'success' : 'error';

    if ($result['success']) {
      header("Location: login.php");
      exit();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Student Management System</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
    }

    h1 {
      color: #333;
      margin-bottom: 30px;
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      color: #555;
      font-weight: 500;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
      transition: border-color 0.3s;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
      outline: none;
      border-color: #667eea;
    }

    .button {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .button:hover {
      transform: translateY(-2px);
    }

    .message {
      padding: 12px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }

    .message.success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .message.error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .links {
      text-align: center;
      margin-top: 20px;
      color: #666;
    }

    .links a {
      color: #667eea;
      text-decoration: none;
      font-weight: 600;
    }

    .links a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Create Account</h1>

    <?php if ($message): ?>
      <div class="message <?php echo $message_type; ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
      </div>

      <button type="submit" class="button">Sign Up</button>
    </form>

    <div class="links">
      Already have an account? <a href="login.php">Log in here</a>
    </div>
  </div>
</body>

</html>