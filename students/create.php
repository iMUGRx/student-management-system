<?php
session_start();
require_once '../db.php';
require_once '../includes/auth.php';

requireLogin();

$user_id = getCurrentUserId();
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $enrollment_number = trim($_POST['enrollment_number'] ?? '');

  if (empty($name) || empty($email)) {
    $message = 'Name and Email are required';
    $message_type = 'error';
  } else {
    $check_query = "SELECT id FROM students WHERE user_id = ? AND email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("is", $user_id, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $message = 'A student with this email already exists';
      $message_type = 'error';
    } else {
      $insert_query = "INSERT INTO students (user_id, name, email, phone, enrollment_number) VALUES (?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($insert_query);
      $stmt->bind_param("issss", $user_id, $name, $email, $phone, $enrollment_number);

      if ($stmt->execute()) {
        header("Location: index.php?success=1");
        exit();
      } else {
        $message = 'Error creating student record';
        $message_type = 'error';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Student - Student Management System</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f5f5;
    }

    .navbar {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 20px 30px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .navbar-content {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar h1 {
      font-size: 24px;
    }

    .navbar a {
      color: white;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 5px;
      background: rgba(255, 255, 255, 0.2);
      transition: background 0.3s;
    }

    .navbar a:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .container {
      max-width: 600px;
      margin: 40px auto;
      padding: 0 20px;
    }

    .form-container {
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #333;
      margin-bottom: 30px;
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
    input[type="tel"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
      transition: border-color 0.3s;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="tel"]:focus {
      outline: none;
      border-color: #667eea;
    }

    .button-group {
      display: flex;
      gap: 10px;
      margin-top: 30px;
    }

    .button {
      flex: 1;
      padding: 12px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .submit-btn {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
    }

    .cancel-btn {
      background: #e9ecef;
      color: #495057;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .cancel-btn:hover {
      background: #dee2e6;
    }

    .message {
      padding: 12px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }

    .message.error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 20px;
      color: #667eea;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="navbar">
    <div class="navbar-content">
      <h1>Student Management System</h1>
      <div>
        <a href="../dashboard.php">Dashboard</a>
        <a href="../logout.php">Log Out</a>
      </div>
    </div>
  </div>

  <div class="container">
    <a href="index.php" class="back-link">← Back to Students</a>

    <div class="form-container">
      <h2>Add New Student</h2>

      <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>">
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group">
          <label for="name">Student Name *</label>
          <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
          <label for="email">Email Address *</label>
          <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone">
        </div>

        <div class="form-group">
          <label for="enrollment_number">Enrollment Number</label>
          <input type="text" id="enrollment_number" name="enrollment_number">
        </div>

        <div class="button-group">
          <button type="submit" class="button submit-btn">Add Student</button>
          <a href="index.php" class="button cancel-btn">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</body>

</html>