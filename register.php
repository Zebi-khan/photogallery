<?php require_once 'header.php' ?>

<?php
$errors = [];
$submitted_values = [
  'first_name' => '',
  'last_name' => '',
  'email' => '',
  'phone' => '',
  'gender' => '',
  'dob' => '',
  'password' => '',
  'confirm_password' => '',
  'terms' => '' // New field for terms and conditions
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $conn = mysqli_connect("localhost", "root", "", "photogallery");
  if ($conn === false) {
    die("Error: Could not connect" . mysqli_connect_error());
  }

  $submitted_values = [
    'first_name' => $_POST['first_name'] ?? '',
    'last_name' => $_POST['last_name'] ?? '',
    'email' => $_POST['email'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'gender' => $_POST['gender'] ?? '',
    'dob' => $_POST['dob'] ?? '',
    'password' => $_POST['password'] ?? '',
    'confirm_password' => $_POST['confirm_password'] ?? '',
    'terms' => $_POST['terms'] ?? '' // Capture terms and conditions
  ];

  $first_name = $submitted_values['first_name'];
  $last_name = $submitted_values['last_name'];
  $email = $submitted_values['email'];
  $phone = $submitted_values['phone'];
  $gender = mysqli_real_escape_string($conn, $submitted_values['gender']);
  $date_of_birth = $submitted_values['dob'];
  $password = $submitted_values['password'];
  $confirm_password = $submitted_values['confirm_password'];
  $terms = $submitted_values['terms']; // Get terms and conditions

  // Validation
  if (empty($first_name)) {
    $errors[] = "First name is required.";
  } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $first_name)) {
    $errors[] = "Only letters and white space allowed in first name.";
  }

  if (empty($last_name)) {
    $errors[] = "Last name is required.";
  } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $last_name)) {
    $errors[] = "Only letters and white space allowed in last name.";
  }

  if (empty($email)) {
    $errors[] = "Email is required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  if (empty($phone)) {
    $errors[] = "Phone number is required.";
  } elseif (!preg_match("/^\+92 \(\d{3}\) \d{3}-\d{4}$/", $phone)) {
    $errors[] = "Phone number must be in the format +92 (___) ___-____.";
  }

  if (empty($date_of_birth)) {
    $errors[] = "Date of birth is required.";
  } elseif (!DateTime::createFromFormat('Y-m-d', $date_of_birth)) {
    $errors[] = "Invalid date format. Use YYYY-MM-DD.";
  }

  $valid_gender = ['Male', 'Female', 'Other'];
  if (!in_array($gender, $valid_gender)) {
    $errors[] = "Invalid gender selection.";
  }

  if (empty($password)) {
    $errors[] = "Password is required.";
  } elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters.";
  }

  if (empty($confirm_password)) {
    $errors[] = "Confirm Password is required.";
  } elseif ($password !== $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Validate terms and conditions
  if (empty($terms)) {
    $errors[] = "You must agree to the terms and conditions.";
  }

  if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    $sql = "INSERT INTO users (first_name, last_name, email, phone, gender, date_of_birth, password, created_at, updated_at) 
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$gender', '$date_of_birth', '$hashed_password', '$created_at', '$updated_at')";

    require_once 'connection.php';

    if (mysqli_query($conn, $sql)) {
      $success_message = "Data stored in the database successfully. Please browse your localhost phpMyAdmin to view the updated data.";
    } else {
      $error_message = "Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
  } else {
    $error_message = '<ul class="list-unstyled">';
    foreach ($errors as $error) {
      $error_message .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $error_message .= '</ul>';
  }
}
?>

<body>
  <script>
    $(document).ready(function() {
      // Apply the Inputmask
      $('#phone').inputmask({
        mask: '+92 (999) 999-9999',
        placeholder: ' ',
        clearIncomplete: true,
        onUnMask: function(maskedValue, unmaskedValue) {
          return unmaskedValue.replace(/^92\s/, ''); // Remove "+92" before returning the unmasked value
        }
      });
    });
  </script>

  <div class="register py-5">
    <div class="container">
      <div class="form-container">
        <div class="card">
          <div class="card-header text-center">
            <h3 class="card-title">Register</h3>
          </div>
          <div class="card-body">
            <?php if (isset($error_message)) : ?>
              <div class="alert alert-danger centered-alert" role="alert">
                <?php echo $error_message; ?>
              </div>
            <?php elseif (isset($success_message)) : ?>
              <div class="alert alert-success centered-alert" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
              </div>
            <?php endif; ?>
            <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="first_name" class="form-label">First Name</label>
                  <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter your first name" value="<?= htmlspecialchars($submitted_values['first_name']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="last_name" class="form-label">Last Name</label>
                  <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter your last name" value="<?= htmlspecialchars($submitted_values['last_name']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" value="<?= htmlspecialchars($submitted_values['email']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="phone" class="form-label">Phone</label>
                  <input type="text" name="phone" class="form-control" id="phone" placeholder="+92 (___) ___-____" value="<?= htmlspecialchars($submitted_values['phone']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="dob" class="form-label">Date of Birth</label>
                  <input type="date" name="dob" class="form-control" id="dob" placeholder="Enter your date of birth" value="<?= htmlspecialchars($submitted_values['dob']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="gender" class="form-label">Gender</label>
                  <select name="gender" class="form-select" id="gender">
                    <option value="" disabled selected>Select your gender</option>
                    <option value="Male" <?= $submitted_values['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $submitted_values['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= $submitted_values['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" value="<?= htmlspecialchars($submitted_values['password']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="confirm_password" class="form-label">Confirm-Password</label>
                  <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Enter your password" value="<?= htmlspecialchars($submitted_values['confirm_password']); ?>">
                </div>

                <div class="col-12 mb-3">
                  <input type="checkbox" class="form-check-input" id="terms" name="terms" value="1" <?= !empty($submitted_values['terms']) ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="terms">Agree to <a href="terms.php">Terms & Conditions</a></label>
                </div>


                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary ps-3 pe-3">Register</button>
                </div>

              </div>
            </form>
          </div>
          <div class="card-footer">
            <p class="text-center mb-0">Already have an account? <a href="/login.php">Login</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+qePfF5xzzr8jQgiKDh9L+0cv5fl4" crossorigin="anonymous"></script>
</body>

</html>