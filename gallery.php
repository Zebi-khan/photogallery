<?php
require_once 'config.php';
require_once 'header.php';

// Fetch all public images
$sql = "SELECT id, image, created_at, user_id 
        FROM images 
        WHERE access_type = 'public'";

require_once 'connection.php';

$result = mysqli_query($conn, $sql);

mysqli_close($conn);

if (!$result) {
  die("Error: " . mysqli_error($conn));
}
?>

<div class="image py-5">
  <div class="container">
    <div class="row">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($image = mysqli_fetch_assoc($result)): ?>
          <div class="col-12 col-sm-6 col-md-4 mb-4" id="image-<?= $image['id']; ?>">
            <div class="card rounded-0 d-flex flex-column">
              <img src="../../uploads/<?= htmlspecialchars($image['image']); ?>" alt="<?= htmlspecialchars($image['image']); ?>" class="img-fluid" style="max-height: 200px; object-fit: center;">
              <div class="card-body text-center">
                <h6 class="card-subtitle mb-2 text-muted">
                  <?= date('Y-m-d', strtotime($image['created_at'])); ?>
                </h6>
              </div>
              <div class="card-footer border-0 text-center">
                <a href="/image.php?id=<?= $image['id']; ?>" class="me-2">View</a>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $image['user_id']): ?>
                  <a href="./user/image/edit_image.php?id=<?= $image['id']; ?>" class="me-2">Edit</a>
                  <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $image['id']; ?>" data-name="<?= htmlspecialchars($image['image']); ?>">Delete</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No public images found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the image "<span id="imageName"></span>"?
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
    let deleteImageId;

    // Trigger modal and pass image ID and name
    document.querySelectorAll('[data-bs-target="#deleteModal"]').forEach(button => {
      button.addEventListener('click', () => {
        deleteImageId = button.getAttribute('data-id');
        document.getElementById('imageName').textContent = button.getAttribute('data-name');
      });
    });

    // Handle the delete confirmation
    document.getElementById('confirmDelete').addEventListener('click', () => {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'delete_image.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (this.status == 200) {
          document.getElementById('image-' + deleteImageId).remove(); // Remove image from the page
          var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
          deleteModal.hide();
        } else {
          alert('An error occurred while deleting the image.');
        }
      };
      xhr.send('id=' + deleteImageId);
    });
  });
</script>