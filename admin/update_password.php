<?php
include('../config.php');
include('../connection.php');

if ($_SESSION['role'] != 'admin') {
  header('Location: ../index.php');
  exit();
}

$admin_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Check if current password is correct
$query = "SELECT password FROM users WHERE id='$admin_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (password_verify($current_password, $user['password'])) {
  if ($new_password === $confirm_password) {
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $update_query = "UPDATE users SET password='$hashed_password' WHERE id='$admin_id'";
    mysqli_query($conn, $update_query);
    $_SESSION['success'] = "Password updated successfully!";
  } else {
    $_SESSION['error'] = "Passwords do not match!";
  }
} else {
  $_SESSION['error'] = "Incorrect current password!";
}

header('Location: settings.php');
