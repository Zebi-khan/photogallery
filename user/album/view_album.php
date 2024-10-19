<?php
require_once '/wamp64/www/photogallery/config.php';
require_once '/wamp64/www/photogallery/header.php';

// Fetch album details based on the album ID
if (isset($_GET['id'])) {
  $album_id = $_GET['id'];

  // Fetch album details
  $album_sql = "SELECT name, slug, thumbnail, description, created_at FROM albums WHERE id = '$album_id' AND access_type = 'public'";
  
  require_once '../../connection.php';

  $album_result = mysqli_query($conn, $album_sql);

  if (!$album_result || mysqli_num_rows($album_result) == 0) {
    echo "<p>Album not found or access is restricted.</p>";
    exit;
  }

  $album = mysqli_fetch_assoc($album_result);

  // Fetch images in the album
  $images_sql = "SELECT id, image FROM images WHERE album_id = '$album_id'";

  require_once '../../connection.php';

  $images_result = mysqli_query($conn, $images_sql);

  mysqli_close($conn);

  if (!$images_result) {
    die("Error: " . mysqli_error($conn));
  }
} else {
  echo "Invalid album ID.";
  exit;
}

?>

<div class="container mt-4">
  <h2><?= htmlspecialchars($album['name']); ?></h2>
  <p><strong>Slug:</strong> <?= htmlspecialchars($album['slug']); ?></p>
  <p><strong>Description:</strong> <?= htmlspecialchars($album['description']); ?></p>
  <p><strong>Created At:</strong> <?= date('Y-m-d', strtotime($album['created_at'])); ?></p>

  <div class="row mt-4">
    <?php if (mysqli_num_rows($images_result) > 0): ?>
      <?php while ($image = mysqli_fetch_assoc($images_result)): ?>
        <div class="col-12 col-sm-6 col-md-4 mb-4">
          <div class="card">
            <img src="../../uploaded_images/<?= htmlspecialchars($image['image']); ?>" class="img-fluid" style="max-height: 200px; object-fit: cover;">
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No images found in this album.</p>
    <?php endif; ?>
  </div>
</div>

<?php require_once '/wamp64/www/photogallery/footer.php'; ?>