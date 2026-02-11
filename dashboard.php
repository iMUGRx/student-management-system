<?php
session_start();
require_once 'db.php';
require_once 'includes/auth.php';

// Require login to access this page
requireLogin();

$username = getCurrentUsername();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Student Management System</title>
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

    .navbar-user {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .navbar-user p {
      font-size: 14px;
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
      max-width: 1200px;
      margin: 40px auto;
      padding: 0 20px;
    }

    .welcome-section {
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 40px;
    }

    .welcome-section h2 {
      color: #333;
      margin-bottom: 15px;
    }

    .welcome-section p {
      color: #666;
      font-size: 16px;
      line-height: 1.6;
    }

    .features {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
    }

    .feature-card {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .feature-card h3 {
      color: #667eea;
      margin-bottom: 15px;
      font-size: 20px;
    }

    .feature-card p {
      color: #666;
      margin-bottom: 20px;
    }

    .feature-card a {
      display: inline-block;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      transition: transform 0.2s;
    }

    .feature-card a:hover {
      transform: translateY(-2px);
    }
  </style>
</head>

<body>
  <div class="navbar">
    <div class="navbar-content">
      <h1>Student Management System</h1>
      <div class="navbar-user">
        <p>Welcome, <strong><?php echo htmlspecialchars($username ?? 'User'); ?></strong></p>
        <a href="logout.php">Log Out</a>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="welcome-section">
      <h2>Welcome to Student Management System</h2>
      <p>Manage your students efficiently. You can add, edit, view, and delete student records with ease.</p>
    </div>

    <div class="features">
      <div class="feature-card">
        <h3>📚 View Students</h3>
        <p>View all your students and their details in one place.</p>
        <a href="students/index.php">Go to Students</a>
      </div>

      <div class="feature-card">
        <h3>➕ Add Student</h3>
        <p>Add a new student record to the system.</p>
        <a href="students/create.php">Add Student</a>
      </div>

      <div class="feature-card">
        <h3>✏️ Manage Records</h3>
        <p>Edit and update student information as needed.</p>
        <a href="students/index.php">Manage Students</a>
      </div>
    </div>
  </div>
</body>

</html>