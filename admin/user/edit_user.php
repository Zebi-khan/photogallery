<?php
require_once '../header.php';
require_once '../../connection.php';

if (isset($_GET['id'])) {
  $user_id = $_GET['id'];
  $query = "SELECT * FROM users WHERE id = $user_id";
  $result = mysqli_query($conn, $query);
  $user = mysqli_fetch_assoc($result);
} else {
  header('Location: manage.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $date_of_birth = $_POST['date_of_birth'];
  $gender = $_POST['gender'];

  $update_query = "UPDATE users SET 
                     first_name='$first_name', 
                     last_name='$last_name', 
                     email='$email', 
                     phone='$phone', 
                     date_of_birth='$date_of_birth', 
                     gender='$gender' 
                     WHERE id=$user_id";

  if (mysqli_query($conn, $update_query)) {
    header('Location: manage.php');
    exit();
  } else {
    echo "<p class='text-danger'>Error updating user details!</p>";
  }
}
?>

<?php require_once '../sidebar.php' ?>

<div class="col-lg-10">
  <div class="container mt-5">
    <h3>Edit User</h3>
    <hr>
    <form method="POST">
      <div class="form-group">
        <label>First Name</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Last Name</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Date of Birth</label>
        <input type="date" name="date_of_birth" value="<?= htmlspecialchars($user['date_of_birth']); ?>" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Gender</label>
        <select name="gender" class="form-control">
          <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
          <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
          <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary my-3">Update User</button>
    </form>
  </div>
</div>

<?php require_once '../footer.php'; ?>