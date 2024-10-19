<?php require_once '../header.php' ?>
<?php require_once '../../config.php' ?>
<!-- albums-Table -->
<?php require_once '../sidebar.php' ?>
<div class="col-lg-10">
  <h4 class="mt-4">Albums</h4>
  <table id="albums-table" class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">User Name</th>
        <th scope="col">Album Name</th>
        <th scope="col">Slug</th>
        <th scope="col">Thumbnail</th>
        <th scope="col">Status</th>
        <th scope="col">Access Type</th>
        <th scope="col">Description</th>
        <th scope="col" style="width: 200px;">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $conn = mysqli_connect("localhost", "root", "", "photogallery");
      $sql = "SELECT albums.*, users.first_name, users.last_name
          FROM albums
          JOIN users ON albums.user_id = users.id";

      require_once '../../connection.php';

      $result = mysqli_query($conn, $sql);

      mysqli_close($conn);

      if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $checked = $row['status'] == 'active' ? 'checked' : '';
          $userName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
          echo "<tr>";
          echo "<th scope='row'>" . $row['id'] . "</th>";
          echo "<td>" . $userName . "</td>";
          echo "<td>" . htmlspecialchars($row['name']) . "</td>";
          echo "<td>" . htmlspecialchars($row['slug']) . "</td>";
          echo "<td><img src='../../uploads/" . htmlspecialchars($row['thumbnail']) . "' width='50' height='50'></td>";
          echo "<td class='status-col'>
              <div class='form-check form-switch'>
                <input class='form-check-input status-switch' type='checkbox' role='switch' data-id='" . $row['id'] . "' $checked>
                <label class='form-check-label'></label>
              </div>
            </td>";
          echo "<td class='album-attributes'>" . htmlspecialchars($row['access_type']) . "</td>";
          echo "<td class='album-attributes'>" . htmlspecialchars($row['description']) . "</td>";
          echo "<td class='action-col'>
                        <a href='../../uploads/" . htmlspecialchars($row['thumbnail']) . "' class='btn btn-success btn-sm fancybox' data-fancybox='gallery' data-caption='" . htmlspecialchars($row['name']) . "'>View</a>
                        <a href='edit_album.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary btn-sm'>Edit</a>
                        <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                      </td>";
          echo "</tr>";
        }
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Modal HTML Structure -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this album?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="cancelDelete">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>
<?php require_once '../../footer.php' ?>

<script>
  $(document).ready(function() {
    $(".fancybox").fancybox({
      buttons: [
        "zoom",
        "share",
        "slideShow",
        "fullScreen",
        "download",
        "thumbs",
        "close"
      ],
      loop: true,
      protect: true,
      beforeShow: function(instance, current) {
        // Code to fetch and populate carousel items dynamically if needed
        var carouselInner = $('#carouselExampleControls .carousel-inner');
        carouselInner.empty(); // Clear previous items

        // Fetch images from the server or use predefined images
        var images = [ /* array of image URLs or data */ ];

        images.forEach((image, index) => {
          var itemClass = index === 0 ? 'carousel-item active' : 'carousel-item';
          var carouselItem = `<div class="${itemClass}">
                              <img src="${image.url}" class="d-block w-100" alt="${image.alt}">
                            </div>`;
          carouselInner.append(carouselItem);
        });

        // Refresh carousel after adding items
        $('#carouselExampleControls').carousel();
      }
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Function to toggle visibility of row content based on the status switch
    function toggleRowColumns(row, isChecked) {
      // Select all table cells except the status column
      const allColumns = row.querySelectorAll('td:not(.status-col)');

      if (isChecked) {
        // If status is 'active', show the content of all columns
        allColumns.forEach(col => col.style.visibility = 'visible');
      } else {
        // If status is 'inactive', hide the content but keep the structure intact
        allColumns.forEach(col => col.style.visibility = 'hidden');
      }
    }

    // Attach event listeners to all status switches
    document.querySelectorAll('.status-switch').forEach(switchElement => {
      const row = switchElement.closest('tr'); // Get the current row

      // Initial check to set visibility based on the status
      const isChecked = switchElement.checked;
      toggleRowColumns(row, isChecked);

      // Add event listener for changes in the switch
      switchElement.addEventListener('change', function() {
        const isChecked = this.checked; // Check the status of the switch
        toggleRowColumns(row, isChecked); // Toggle visibility
      });
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    let albumsId;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function() {
        albumsId = this.getAttribute('data-id');
        deleteModal.show();
      });
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
      fetch(`delete_album.php?id=${albumsId}`, {
          method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Error deleting album.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    });

    // Add event listener to cancel button to refresh the page and close the modal
    document.getElementById('cancelDelete').addEventListener('click', function() {
      location.reload(); // Refresh the page
      deleteModal.hide(); // Hide the modal
    });
  });
</script>