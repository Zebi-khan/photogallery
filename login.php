<?php
require_once 'config.php';
require_once 'database.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Fetch user from the database
  $stmt = $conn->prepare("SELECT id, first_name, password, role FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $first_name, $hashed_password, $role);
    $stmt->fetch();

    // Verify password
    if (password_verify($password, $hashed_password)) {
      // Check if already logged in as admin/user
      if ($role == 'admin' && isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
        echo "A user is currently logged in. Please log out as user first.";
        exit();
      } elseif ($role == 'user' && isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
        echo "An admin is currently logged in. Please log out as admin first.";
        exit();
      }

      // Properly unset the previous session
      session_unset();

      // Check role for admin and user
      if ($role == 'admin') {
        // Set session variables for admin
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $first_name;
        $_SESSION['role'] = 'admin';
        $_SESSION['admin_logged_in'] = true;

        // Redirect to admin dashboard
        header("Location: /admin/index.php");
        exit();
      } elseif ($role == 'user') {
        // Set session variables for user
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $first_name;
        $_SESSION['role'] = 'user';
        $_SESSION['user_logged_in'] = true;

        // Redirect to user dashboard
        header("Location: /");
        exit();
      } else {
        echo "Invalid role.";
      }
    } else {
      echo "Invalid password.";
    }
  } else {
    echo "No user found with this email.";
  }

  $stmt->close();
}
?>


<?php require_once 'header.php'; ?>

<div class="login py-5">
  <div class="container d-flex justify-content-center">
    <div class="form-container col-md-6">
      <div class="card">
        <div class="card-header text-center">
          <h3 class="card-title">Login</h3>
        </div>
        <div class="card-body">
          <form method="post" action="">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
            </div>
            <div class=" form-check">
              <div class="row">
                <div class="col-6">
                  <input type="checkbox" class="form-check-input" id="terms">
                  <label class="form-check-label" for="terms">Remember me</label>
                </div>
                <div class="col-6">
                  <p class="text-end">
                    <a href="forget.php" class="text-decoration-none">Forgot Password?</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="row d-flex">
              <div class="col-6">
                <button type="submit" class="btn btn-primary w-50 mb-3">Login</button>
              </div>
            </div>
          </form>
        </div>
        <div class="card-footer">
          <p class="text-center mb-0">Don't have an account? <a href="/register.php">Register</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>