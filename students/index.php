<?php
session_start();
require_once '../db.php';
require_once '../includes/auth.php';

// Require login to access this page
requireLogin();

$user_id = getCurrentUserId();
$message = '';
$message_type = '';

// Fetch all students for the current user
$query = "SELECT id, name, email, phone, enrollment_number, created_at FROM students WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Students - Student Management System</title>
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

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .header h2 {
      color: #333;
    }

    .add-btn {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      cursor: pointer;
      transition: transform 0.2s;
      display: inline-block;
    }

    .add-btn:hover {
      transform: translateY(-2px);
    }

    .table-container {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead {
      background: #f8f9fa;
      border-bottom: 2px solid #dee2e6;
    }

    th {
      padding: 15px;
      text-align: left;
      font-weight: 600;
      color: #495057;
    }

    td {
      padding: 15px;
      border-bottom: 1px solid #dee2e6;
    }

    tr:hover {
      background: #f8f9fa;
    }

    .actions {
      display: flex;
      gap: 10px;
    }

    .edit-btn,
    .delete-btn {
      padding: 8px 15px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      cursor: pointer;
      font-size: 14px;
      transition: transform 0.2s;
    }

    .edit-btn {
      background: #ffc107;
      color: #333;
    }

    .edit-btn:hover {
      transform: translateY(-2px);
    }

    .delete-btn {
      background: #dc3545;
      color: white;
    }

    .delete-btn:hover {
      transform: translateY(-2px);
    }

    .empty-message {
      text-align: center;
      padding: 40px;
      color: #999;
      font-size: 16px;
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
      <div class="navbar-user">
        <a href="../dashboard.php">Dashboard</a>
        <a href="../logout.php">Log Out</a>
      </div>
    </div>
  </div>

  <div class="container">
    <a href="../dashboard.php" class="back-link">← Back to Dashboard</a>

    <div class="header">
      <h2>My Students</h2>
      <a href="create.php" class="add-btn">+ Add New Student</a>
    </div>

    <?php if (empty($students)): ?>
      <div class="table-container">
        <div class="empty-message">
          No students yet. <a href="create.php">Create one now!</a>
        </div>
      </div>
    <?php else: ?>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Enrollment Number</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($students as $student): ?>
              <tr>
                <td><?php echo htmlspecialchars($student['name']); ?></td>
                <td><?php echo htmlspecialchars($student['email']); ?></td>
                <td><?php echo htmlspecialchars($student['phone'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($student['enrollment_number'] ?? 'N/A'); ?></td>
                <td>
                  <div class="actions">
                    <a href="edit.php?id=<?php echo $student['id']; ?>" class="edit-btn">Edit</a>
                    <form method="POST" action="delete.php" style="display: inline;">
                      <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                      <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this student?');">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</body>

</html>