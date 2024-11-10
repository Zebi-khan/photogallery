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
  'album_id' => '',
  'status' => '',
  'access_type' => '',
];

$conn = mysqli_connect("localhost", "root", "", "photogallery");
if ($conn === false) {
  die("Error: Could not connect" . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $submitted_values = [
    'album_id' => $_POST['album_id'] ?? '',
    'status' => $_POST['status'] ?? '',
    'access_type' => $_POST['access_type'] ?? '',
  ];

  $album_id = mysqli_real_escape_string($conn, $submitted_values['album_id']);
  $status = mysqli_real_escape_string($conn, $submitted_values['status']);
  $access_type = mysqli_real_escape_string($conn, $submitted_values['access_type']);

  // Handle file upload
  if (isset($_FILES['image']) && count($_FILES['image']['name']) > 0) {
    $file_count = count($_FILES['image']['name']);
    for ($i = 0; $i < $file_count; $i++) {
      $file_error = $_FILES['image']['error'][$i];

      if ($file_error === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'][$i];
        $original_file_name = $_FILES['image']['name'][$i];
        $file_extension = pathinfo($original_file_name, PATHINFO_EXTENSION);

        // Generate a unique filename
        $unique_file_name = pathinfo($original_file_name, PATHINFO_FILENAME) . '_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_dir = '../uploads/';
        $upload_file = $upload_dir . $unique_file_name;

        // Check file size
        $file_size = $_FILES['image']['size'][$i];
        $max_file_size = 20 * 1024 * 1024; // 20 MB
        if ($file_size > $max_file_size) {
          $errors[] = "File size of '$original_file_name' exceeds the maximum limit of 20 MB.";
        } else {
          if (move_uploaded_file($file_tmp, $upload_file)) {
            // Escape the file names for database storage
            $escaped_original_name = mysqli_real_escape_string($conn, $original_file_name);
            $escaped_unique_name = mysqli_real_escape_string($conn, $unique_file_name);

            // Insert each image into the database
            $created_at = date('Y-m-d H:i:s');
            $updated_at = $created_at;

            $sql = "INSERT INTO images (album_id, user_id, original_image, image, status, access_type, created_at, updated_at) 
                    VALUES ('$album_id', '$user_id', '$escaped_original_name', '$escaped_unique_name', '$status', '$access_type', '$created_at', '$updated_at')";
            
            require_once '../connection.php';

            if (!mysqli_query($conn, $sql)) {
              $errors[] = "Error inserting image into database: " . mysqli_error($conn);
            }
          } else {
            $errors[] = "Failed to upload '$original_file_name'.";
          }
        }
      } else {
        $errors[] = "File upload error code: " . $file_error . " for file '$original_file_name'.";
      }
    }
  } else {
    $errors[] = "No image uploaded.";
  }

  if (empty($album_id)) {
    $errors[] = "Album selection is required.";
  }

  if (empty($status)) {
    $errors[] = "Status is required.";
  }

  if (empty($access_type)) {
    $errors[] = "Access Type is required.";
  }

  if (!empty($errors)) {
    $error_message = '<ul class="list-unstyled">';
    foreach ($errors as $error) {
      $error_message .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $error_message .= '</ul>';
  } else {
    $success_message = "Images uploaded successfully and stored in the database.";
  }
}

$album_sql = "SELECT id, name FROM albums WHERE user_id = '$user_id'";
require_once '../connection.php';
$album_result = mysqli_query($conn, $album_sql);

mysqli_close($conn);
?>

<body>
  <div class="py-5">
    <div class="container">
      <!-- Form -->
      <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="card p-0">
            <div class="card-header">
              <h2 class="text-center">Add Image</h2>
            </div>
            <div class="card-body p-0">
              <?php if (isset($error_message)) : ?>
                <div class="alert alert-danger text-center" role="alert">
                  <?php echo $error_message; ?>
                </div>
              <?php elseif (isset($success_message)) : ?>
                <div class="alert alert-success text-center" role="alert">
                  <?php echo htmlspecialchars($success_message); ?>
                </div>
              <?php endif; ?>
              <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                <div class="row p-2">
                  <div class="col-md-6 mb-3">
                    <label for="choose_album" class="form-label">Choose Album</label>
                    <select class="form-control" id="choose_album" name="album_id" required>
                      <option value="">Select an Album</option>
                      <?php while ($album = mysqli_fetch_assoc($album_result)) : ?>
                        <option value="<?php echo htmlspecialchars($album['id']); ?>" <?php echo $submitted_values['album_id'] == $album['id'] ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($album['name']); ?>
                        </option>
                      <?php endwhile; ?>
                    </select>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Images</label>
                    <input type="file" class="form-control" id="image" name="image[]" multiple required>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                      <option value="active" <?= $submitted_values['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                      <option value="inactive" <?= $submitted_values['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="access_type" class="form-label">Access Type</label>
                    <select class="form-control" id="access_type" name="access_type" required>
                      <option value="public" <?= $submitted_values['access_type'] == 'public' ? 'selected' : ''; ?>>Public</option>
                      <option value="private" <?= $submitted_values['access_type'] == 'private' ? 'selected' : ''; ?>>Private</option>
                    </select>
                  </div>
                </div>
                <div class="card-footer text-center">
                  <button type="submit" class="btn btn-primary rounded-0">Upload Image</button>
                </div> <!-- card-footer -->
              </form>
            </div> <!-- card -->
          </div> <!-- col-md-10 -->
        </div> <!-- row -->
      </div> <!-- container -->
    </div>
  </div>

<?php require_once '../footer.php';?>
