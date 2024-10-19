<?php
require_once '/wamp64/www/photogallery/config.php';
require_once '/wamp64/www/photogallery/header.php';

// Fetch all public albums
$sql = "SELECT id, name, slug, thumbnail, created_at, user_id 
        FROM albums 
        WHERE access_type = 'public'";

require_once '../../connection.php';

$result = mysqli_query($conn, $sql);

mysqli_close($conn);

if (!$result) {
  die("Error: " . mysqli_error($conn));
}
?>

<div class="album py-5">
  <div class="container">
    <div class="row">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($album = mysqli_fetch_assoc($result)): ?>
          <div class="col-12 col-sm-6 col-md-4 mb-4" id="album-<?= $album['id']; ?>">
            <div class="card rounded-0 d-flex flex-column">
              <img src="../../uploads/<?= htmlspecialchars($album['thumbnail']); ?>" alt="<?= htmlspecialchars($album['name']); ?>" class="img-fluid" style="max-height: 200px; object-fit: center;">
              <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($album['name']); ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">
                  <?= date('Y-m-d', strtotime($album['created_at'])); ?>
                </h6>
              </div>
              <div class="card-footer border-0 text-center">
                <a href="view_album.php?id=<?= $album['id']; ?>" class="me-2">View</a>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $album['user_id']): ?>
                  <a href="edit_album.php?id=<?= $album['id']; ?>" class="me-2">Edit</a>
                  <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $album['id']; ?>" data-name="<?= htmlspecialchars($album['name']); ?>">Delete</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No public albums found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete Album</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the album "<span id="albumName"></span>"?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    let deleteAlbumId;

    // Trigger modal and pass album ID and name
    document.querySelectorAll('[data-bs-target="#deleteModal"]').forEach(button => {
      button.addEventListener('click', () => {
        deleteAlbumId = button.getAttribute('data-id');
        document.getElementById('albumName').textContent = button.getAttribute('data-name');
      });
    });

    // Handle the delete confirmation
    document.getElementById('confirmDelete').addEventListener('click', () => {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'delete_album.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (this.status == 200) {
          document.getElementById('album-' + deleteAlbumId).remove(); // Remove album from the page
          var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
          deleteModal.hide();
        } else {
          alert('An error occurred while deleting the album.');
        }
      };
      xhr.send('id=' + deleteAlbumId);
    });
  });
</script>