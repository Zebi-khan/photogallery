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
?>

<?php require_once '../sidebar.php' ?>

<div class="col-lg-10">
  <div class="container mt-5">
    <h3>View User</h3>
    <hr>
    <div class="card">
      <div class="card-body">
        <p><strong>First Name:</strong> <?= htmlspecialchars($user['first_name']); ?></p>
        <p><strong>Last Name:</strong> <?= htmlspecialchars($user['last_name']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']); ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($user['date_of_birth']); ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender']); ?></p>
      </div>
    </div>
  </div>
</div>

<?php require_once '../footer.php'; ?>