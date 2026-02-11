<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
function isLoggedIn()
{
  return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}
function requireLogin()
{
  if (!isLoggedIn()) {
    header("Location: /student-management-system/login.php");
    exit();
  }
}
function getCurrentUserId()
{
  return $_SESSION['user_id'] ?? null;
}
function getCurrentUsername()
{
  return $_SESSION['username'] ?? null;
}
function registerUser($username, $email, $password)
{
  global $conn;
  $check_query = "SELECT id FROM users WHERE username = ? OR email = ?";
  $stmt = $conn->prepare($check_query);
  $stmt->bind_param("ss", $username, $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    return ["success" => false, "message" => "Username or email already exists"];
  }

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($insert_query);
  $stmt->bind_param("sss", $username, $email, $hashed_password);

  if ($stmt->execute()) {
    return ["success" => true, "message" => "Registration successful. Please log in."];
  } else {
    return ["success" => false, "message" => "Error during registration"];
  }
}
function loginUser($username, $password)
{
  global $conn;

  $query = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    return ["success" => false, "message" => "User not found"];
  }

  $user = $result->fetch_assoc();

  if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    return ["success" => true, "message" => "Login successful"];
  } else {
    return ["success" => false, "message" => "Invalid password"];
  }
}
function logoutUser()
{
  session_destroy();
  return ["success" => true, "message" => "Logged out successfully"];
}
