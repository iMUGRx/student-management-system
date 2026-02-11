<?php
session_start();
require_once '../db.php';
require_once '../includes/auth.php';

requireLogin();

$user_id = getCurrentUserId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $student_id = $_POST['id'] ?? null;

  if ($student_id) {
    $query = "SELECT id FROM students WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $student_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $delete_query = "DELETE FROM students WHERE id = ? AND user_id = ?";
      $stmt = $conn->prepare($delete_query);
      $stmt->bind_param("ii", $student_id, $user_id);
      $stmt->execute();
    }
  }
}
header("Location: index.php");
exit();
