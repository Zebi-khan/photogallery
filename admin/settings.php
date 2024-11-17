<?php
include('../config.php');
include('header.php');
include('../connection.php');

// Check if admin is logged in
if ($_SESSION['role'] != 'admin') {
  header('Location: ../index.php');
  exit();
}

// Fetch current admin data
$admin_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$admin_id'";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);
?>

<?php require_once 'sidebar.php'?>

<div class="col-lg-10">
  <div class="container mt-5">
    <h2>Admin Settings</h2>
    <hr>

    <!-- Profile Information Section -->
    <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="mb-5">
      <h4>Profile Information</h4>
      <div class="form-group">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?= $admin['first_name']; ?>" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?= $admin['last_name']; ?>" class="form-control" required>
      </div>
      <div class="form-group mb-3">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= $admin['email']; ?>" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <!-- Password Change Section -->
    <form action="update_password.php" method="POST">
      <h4>Change Password</h4>
      <div class="form-group">
        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <div class="form-group mb-3">
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-warning mb-3">Change Password</button>
    </form>
  </div>
</div>

<?php include('footer.php'); ?>