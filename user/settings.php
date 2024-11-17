<?php
include '../config.php';
include '../header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Fetch the user's current information from the database
$user_id = $_SESSION['user_id'];
include '../connection.php';
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Update Profile Information
if (isset($_POST['update_profile'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    $update_query = "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email' WHERE id='$user_id'";
    mysqli_query($conn, $update_query);
    $_SESSION['success_message'] = "Profile updated successfully!";
    header("Location: index.php");
    exit;
}

// Change Password
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $password_query = "UPDATE users SET password='$hashed_password' WHERE id='$user_id'";
            mysqli_query($conn, $password_query);
            $_SESSION['success_message'] = "Password changed successfully!";
        } else {
            $_SESSION['error_message'] = "New passwords do not match.";
        }
    } else {
        $_SESSION['error_message'] = "Incorrect current password.";
    }
    header("Location: settings.php");
    exit;
}
?>

<div class="container mt-5">
    <h2>Account Settings</h2>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <!-- Profile Update Form -->
    <form method="POST" enctype="multipart/form-data">
        <h4>Profile Information</h4>
        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?php echo $user['first_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?php echo $user['last_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
    </form>

    <hr>

    <!-- Change Password Form -->
    <form method="POST" class="mt-4">
        <h4>Change Password</h4>
        <div class="mb-3">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" name="change_password" class="btn btn-warning mb-5">Change Password</button>
    </form>
</div>

<?php include '../footer.php' ?>
