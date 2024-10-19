<?php
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
  // Redirect to login page if user is not logged in
  header("Location: /login.php");
  exit();
}

require_once '../header.php';

$errors = [];
$submitted_values = [
  'name' => '',
  'slug' => '',
  'thumbnail' => '',
  'status' => '',
  'access_type' => '',
  'created_at' => '',
  'updated_at' => '',
  'description' => ''
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $conn = mysqli_connect("localhost", "root", "", "photogallery");
  if ($conn === false) {
    die("Error: Could not connect" . mysqli_connect_error());
  }

  // Retrieve form data
  $submitted_values = [
    'name' => $_POST['name'] ?? '',
    'slug' => $_POST['slug'] ?? '',
    'status' => $_POST['status'] ?? '',
    'access_type' => $_POST['access_type'] ?? '',
    'created_at' => $_POST['created_at'] ?? '',
    'updated_at' => $_POST['updated_at'] ?? '',
    'description' => $_POST['description'] ?? ''
  ];

  $name = mysqli_real_escape_string($conn, $submitted_values['name']);
  $slug = mysqli_real_escape_string($conn, $submitted_values['slug']);
  $status = mysqli_real_escape_string($conn, $submitted_values['status']);
  $access_type = mysqli_real_escape_string($conn, $submitted_values['access_type']);
  $created_at = mysqli_real_escape_string($conn, $submitted_values['created_at']);
  $updated_at = mysqli_real_escape_string($conn, $submitted_values['updated_at']);
  $description = mysqli_real_escape_string($conn, $submitted_values['description']);

  // Handle file upload
  if (isset($_FILES['thumbnail'])) {
    $file_error = $_FILES['thumbnail']['error'];

    if ($file_error === UPLOAD_ERR_OK) {
      $file_tmp = $_FILES['thumbnail']['tmp_name'];
      $file_name = basename($_FILES['thumbnail']['name']);
      $upload_dir = '../uploads/'; // Set the correct path to the uploads directory
      $upload_file = $upload_dir . $file_name;

      // Check file size
      $file_size = $_FILES['thumbnail']['size'];
      $max_file_size = 20 * 1024 * 1024; // 20 MB
      if ($file_size > $max_file_size) {
        $errors[] = "File size exceeds the maximum limit of 20 MB.";
      } else {
        if (move_uploaded_file($file_tmp, $upload_file)) {
          $thumbnail = $file_name; // Set the thumbnail to the file name after successful upload
        } else {
          $errors[] = "Sorry, there was an error uploading your file.";
        }
      }
    } else {
      $errors[] = "File upload error code: " . $file_error;
    }
  } else {
    $errors[] = "No thumbnail images uploaded.";
  }

  // Validation
  if (empty($name)) {
    $errors[] = "Album name is required.";
  } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
    $errors[] = "Only letters and white space allowed in album name.";
  }

  if (empty($slug)) {
    $errors[] = "Slug is required.";
  } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $slug)) {
    $errors[] = "Invalid slug format.";
  }

  if (empty($status)) {
    $errors[] = "Status is required.";
  }

  if (empty($access_type)) {
    $errors[] = "Access Type is required.";
  }

  if (empty($description)) {
    $errors[] = "Description is required.";
  }

  // Handle errors and insert data
  if (!empty($errors)) {
    $error_message = '<ul class="list-unstyled">';
    foreach ($errors as $error) {
      $error_message .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $error_message .= '</ul>';
  } else {
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID from the session
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    $sql = "INSERT INTO albums (user_id, name, slug, thumbnail, status, access_type, created_at, updated_at, description) 
            VALUES ('$user_id', '$name', '$slug', '$thumbnail', '$status', '$access_type', '$created_at', '$updated_at', '$description')";
   
   require_once '../connection.php';

   if (mysqli_query($conn, $sql)) {
      $success_message = "Data stored in the database successfully. Please browse your localhost phpMyAdmin to view the updated data.";
    } else {
      $error_message = "Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
  }
}
?>

<body>
  <div class="py-5">
    <div class="container">
      <!-- form -->
      <div class="row justify-content-center">
        <div class="col-md-12 ms-4">
          <div class="card">
            <div class="card-header">
              <h2 class="text-center">Add Album</h2>
            </div>
            <div class="card-body p-0">
              <?php if (isset($error_message)) : ?>
                <div class="alert alert-danger centered-alert" role="alert">
                  <?php echo $error_message; ?>
                </div>
              <?php elseif (isset($success_message)) : ?>
                <div class="alert alert-success centered-alert" role="alert">
                  <?php echo htmlspecialchars($success_message); ?>
                </div>
              <?php endif; ?>
              <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                <div class="row p-3">
                  <div class="col-md-6">
                    <label for="name" class="form-label">Album Name</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter album name" value="<?= htmlspecialchars($submitted_values['name']); ?>"  onkeyup="generateSlug()">
                  </div>

                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="slug" class="form-label">Slug</label>
                      <input type="text" class="form-control" id="slug" name="slug" value="<?= htmlspecialchars($submitted_values['slug']); ?>" readonly required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="mb-3">
                      <label for="thumbnail" class="form-label">Thumbnail</label>
                      <input type="file" class="form-control" id="thumbnail" name="thumbnail" required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="mb-3">
                      <label for="status" class="form-label">Status</label>
                      <select class="form-control" id="status" name="status" required>
                        <option value="active" <?= $submitted_values['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?= $submitted_values['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="mb-3">
                      <label for="access_type" class="form-label">Access Type</label>
                      <select class="form-control" id="access_type" name="access_type" required>
                        <option value="public" <?= $submitted_values['access_type'] == 'public' ? 'selected' : ''; ?>>Public</option>
                        <option value="private" <?= $submitted_values['access_type'] == 'private' ? 'selected' : ''; ?>>Private</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="mb-3">
                      <label for="description" class="form-label">Description</label>
                      <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($submitted_values['description']); ?></textarea>
                    </div>
                  </div>

                </div>
                <div class="card-footer text-center">
                  <button type="submit" class="btn btn-primary rounded-0 ps-4 pe-4">Create</button>
                </div>
              </form>
            </div>
          </div> <!-- card body -->
        </div> <!-- card -->
      </div> <!-- col -->
    </div> <!-- row -->
    <!-- container -->
  </div>

  <?php require_once '../footer.php'; ?>
</body>

</html>