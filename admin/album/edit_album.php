<?php
require_once '../header.php';

// Fetch the album details based on the album ID
if (isset($_GET['id'])) {
  $album_id = $_GET['id'];
  $sql = "SELECT * FROM albums WHERE id = '$album_id'";

  require_once '../../connection.php';

  $result = mysqli_query($conn, $sql);

  if (!$result) {
    die("Error: " . mysqli_error($conn));
  }

  $album = mysqli_fetch_assoc($result);

  if (!$album) {
    header("Location: albums.php?error=Album not found.");
    exit;
  }
} else {
  header("Location: albums.php?error=Invalid album ID.");
  exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $slug = mysqli_real_escape_string($conn, $_POST['slug']);
  $created_at = mysqli_real_escape_string($conn, $_POST['created_at']);
  $access_type = mysqli_real_escape_string($conn, $_POST['access_type']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);

  // Handle the image upload
  $thumbnail = $album['thumbnail']; // Use the existing image as default
  if (!empty($_FILES['thumbnail']['name'])) {
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES['thumbnail']['name']);
    if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)) {
      $thumbnail = $_FILES['thumbnail']['name']; // Update with the new image
    } else {
      $error_message = "Error uploading file.";
    }
  }

  // Update the album details
  $update_sql = "UPDATE albums SET name = '$name', slug = '$slug', thumbnail = '$thumbnail', created_at = '$created_at', access_type = '$access_type', description = '$description' WHERE id = '$album_id'";

  require_once '../../connection.php';

  if (mysqli_query($conn, $update_sql)) {
    header("Location: ../index.php?success=Album updated successfully.");
    exit;
  } else {
    $error_message = "Error: " . mysqli_error($conn);
  }
}

mysqli_close($conn);
?>

<?php require_once '../sidebar.php' ?>

<div class="col-lg-10">
  <div class="edit py-5">
    <div class="container d-flex justify-content-center">
      <div class="form-container">
        <div class="card">
          <div class="card-body m-0 p-0">
            <div class="card-header text-center">
              <h2 class="card-title">Edit Album</h2>
            </div>
            <form method="POST" enctype="multipart/form-data">
              <div class="row p-2">
                <div class="col-6">
                  <div class="mb-3">
                    <label for="name" class="form-label">Album Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($album['name']); ?>" required>
                  </div>
                </div>
                <div class="col-6">
                  <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" value="<?= htmlspecialchars($album['slug']); ?>" required>
                  </div>
                </div>
                <div class="col-6">
                  <div class="mb-3">
                    <label for="created_at" class="form-label">Created At</label>
                    <input type="date" class="form-control" id="created_at" name="created_at" value="<?= htmlspecialchars($album['created_at']); ?>" required>
                  </div>
                </div>
                <div class="col-6">
                  <div class="mb-3">
                    <label for="access_type" class="form-label">Access Type</label>
                    <select class="form-select" id="access_type" name="access_type" required>
                      <option value="public" <?= $album['access_type'] == 'public' ? 'selected' : ''; ?>>Public</option>
                      <option value="private" <?= $album['access_type'] == 'private' ? 'selected' : ''; ?>>Private</option>
                    </select>
                  </div>
                </div>
                <div class="col-6">
                  <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($album['description']); ?></textarea>
                  </div>
                </div>
                <div class="col-6">
                  <div class="mb-3">
                    <label for="thumbnail" class="form-label">Thumbnail</label>
                    <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                    <p>Current Image: <img src="../../uploads/<?= htmlspecialchars($album['thumbnail']); ?>" alt="<?= htmlspecialchars($album['name']); ?>" class="img-fluid" style="max-width: 100px; margin-top: 10px"></p>
                  </div>
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
</div>

<?php require_once '/wamp64/www/photogallery/footer.php'; ?>