<?php
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /login.php");
    exit();
}

require_once '../header.php';

// Fetch albums created by the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT a.id, a.name, a.slug, a.thumbnail, a.created_at, 
               (SELECT COUNT(*) FROM images i WHERE i.album_id = a.id) AS image_count
        FROM albums a
        WHERE a.user_id = '$user_id'";

require_once '../connection.php';

$result = mysqli_query($conn, $sql);

mysqli_close($conn);
?>

<div class="album py-5">
  <div class="container">
    <div class="mb-5 d-flex justify-content-end">
      <a href="create.php" class="btn btn-primary rounded-0 ps-3 pe-3">Add Album</a>
    </div>
    <div class="row">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while($album = mysqli_fetch_assoc($result)): ?>
          <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="card rounded-0 shadow position-relative">
              <div class="position-relative">
                <img src="../uploads/<?= htmlspecialchars($album['thumbnail']); ?>" alt="<?= htmlspecialchars($album['name']); ?>" class="img-fluid mb-3" style="width: 100%; height: 200px; object-fit: cover;">
                <span class="badge bg-primary position-absolute end-0 rounded-0"><?= htmlspecialchars($album['image_count']); ?> Images</span>
              </div>
              <div class="card-body p-0">
                <h5 class="ms-2"><?= htmlspecialchars($album['name']); ?></h5>
                <h6 class="card-subtitle ms-2 mb-2">
                  <?= date('Y-m-d', strtotime($album['created_at'])); ?>
                </h6>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No albums found. Create a new album to get started.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once '../footer.php'; ?>