<?php
// Start output buffering
ob_start();

require_once '../../config.php';
require_once '../../header.php';

// Fetch the image details based on the image ID
if (isset($_GET['id'])) {
  $image_id = $_GET['id'];
  $sql = "SELECT * FROM images WHERE id = '$image_id'";

  require_once '../../connection.php';

  $result = mysqli_query($conn, $sql);

  if (!$result) {
    die("Error: " . mysqli_error($conn));
  }

  $image = mysqli_fetch_assoc($result);

  if (!$image) {
    header("Location: gallery.php?error=Image not found.");
    ob_end_flush(); // End output buffering and flush output
    exit;
  }
} else {
  header("Location: gallery.php?error=Invalid image ID.");
  ob_end_flush(); // End output buffering and flush output
  exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $access_type = mysqli_real_escape_string($conn, $_POST['access_type']);

  // Handle the image upload
  $image_name = $image['image']; // Use the existing image as default
  if (!empty($_FILES['image']['name'])) {
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES['image']['name']);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
      $image_name = $_FILES['image']['name']; // Update with the new image
    } else {
      echo "Error uploading image.";
      ob_end_flush(); // End output buffering and flush output
      exit;
    }
  }

  // Update the image details
  $update_sql = "UPDATE images SET image = '$image_name', access_type = '$access_type' WHERE id = '$image_id'";

  require_once '../../connection.php';

  if (mysqli_query($conn, $update_sql)) {
    header("Location: ../index.php?success=Image updated successfully.");
    ob_end_flush(); // End output buffering and flush output
    exit;
  } else {
    echo "Error: " . mysqli_error($conn);
    ob_end_flush(); // End output buffering and flush output
    exit;
  }
}

?>

<div class="edit py-5">
  <div class="container">
    <div class="form-container">
      <div class="card">
        <div class="card-body p-0">
          <div class="card-header">
            <h2 class="text-center">Edit Image</h2>
          </div>
          <form method="POST" enctype="multipart/form-data">
            <div class="row p-2">
              <div class="col-6">
                <div class="mb-3">
                  <label for="access_type" class="form-label">Access Type</label>
                  <select class="form-select" id="access_type" name="access_type" required>
                    <option value="public" <?= $image['access_type'] == 'public' ? 'selected' : ''; ?>>Public</option>
                    <option value="private" <?= $image['access_type'] == 'private' ? 'selected' : ''; ?>>Private</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="mb-3">
                  <label for="image" class="form-label">Image</label>
                  <input type="file" class="form-control" id="image" name="image">
                </div>
              </div>
              <div class="col-6">
                <p>Current Image: <img src="../../uploads/<?= htmlspecialchars($image['image']); ?>" class="img-fluid" style="max-width: 100px; margin-top:20px"></p>
              </div>
            </div>
            <div class="card-footer text-center">
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once '../../footer.php'; ?>

<?php
// End output buffering and flush
ob_end_flush();
?>