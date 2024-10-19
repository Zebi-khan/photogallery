<?php
require_once '../config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to login page if user is not logged in
  header("Location: /login.php");
  exit();
}

// Load the header
require_once '/wamp64/www/photogallery/admin/header.php';

// Establish a database connection
$conn = mysqli_connect("localhost", "root", "", "photogallery");
if ($conn === false) {
  die("Error: Could not connect" . mysqli_connect_error());
}

// Count the number of albums
$album_count_sql = "SELECT COUNT(*) as total_albums FROM albums";
$album_count_result = mysqli_query($conn, $album_count_sql);
$album_count_row = mysqli_fetch_assoc($album_count_result);
$total_albums = $album_count_row['total_albums'];

// Count the number of images
$image_count_sql = "SELECT COUNT(*) as total_images FROM images";
$image_count_result = mysqli_query($conn, $image_count_sql);
$image_count_row = mysqli_fetch_assoc($image_count_result);
$total_images = $image_count_row['total_images'];

// Count the number of users
$user_count_sql = "SELECT COUNT(*) as total_user FROM users";
$user_count_result = mysqli_query($conn, $user_count_sql);
$user_count_row = mysqli_fetch_assoc($user_count_result);
$total_user = $user_count_row['total_user'];

mysqli_close($conn);
?>

<!-- Sidebar -->
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-2 p-0">
      <nav class="sidebar sidebar-border p-3">
        <a href="/admin/index.php" class="fs-4 mt-5 ms-3 text-decoration-none">Dashboard</a>
        <ul class="nav flex-column">
          <li class="nav-item mb-5 mt-5">
            <a class="nav-link" href="./album/manage.php">Manage albums</a>
          </li>
          <li class="nav-item mb-5">
            <a class="nav-link" href="./image/manage.php">Manage Gallery</a>
          </li>
          <li class="nav-item mb-5">
            <a class="nav-link" href="/admin/user/manage.php">Manage Users</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Settings</a>
          </li>
        </ul>
      </nav>
    </div>
    <div class="col-lg-10">
      <div class="main-content py-5 d-flex align-items-center justify-content-center">
        <div class="container">
          <div class="row gx-5">
            <!-- Widget 1 -->
            <div class="col-md-6 col-lg-3">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title text-center"><span style="color: red;"><?= $total_albums; ?></span> Albums</h5>
                </div>
              </div>
            </div>
            <!-- Widget 2 -->
            <div class="col-md-6 col-lg-3">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title text-center"><span style="color: blue;"><?= $total_images; ?></span> Images</h5>
                </div>
              </div>
            </div>
            <!-- Widget 3 -->
            <div class="col-md-6 col-lg-3">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title text-center"><span style="color: green;"><?= $total_user; ?></span> Users</h5>
                </div>
              </div>
            </div>
            <!-- Widget 4 -->
            <div class="col-md-6 col-lg-3">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">0 Comments</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- albums-Table -->
      <h4>Albums</h4>
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
          JOIN users ON albums.user_id = users.id
          ORDER BY albums.created_at DESC
          LIMIT 3";

          require_once '../connection.php';

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
              echo "<td><img src='../uploads/" . htmlspecialchars($row['thumbnail']) . "' width='50' height='50'></td>";
              echo "<td class='status-col'>
              <div class='form-check form-switch'>
                <input class='form-check-input status-switch' type='checkbox' role='switch' data-id='" . $row['id'] . "' $checked>
                <label class='form-check-label'></label>
              </div>
            </td>";
              echo "<td class='album-attributes'>" . htmlspecialchars($row['access_type']) . "</td>";
              echo "<td class='album-attributes'>" . htmlspecialchars($row['description']) . "</td>";
              echo "<td>
                        <a href='../uploads/" . htmlspecialchars($row['thumbnail']) . "' class='btn btn-success btn-sm fancybox' data-fancybox='gallery' data-caption='" . htmlspecialchars($row['name']) . "'>View</a>
                        <a href='album/edit_album.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary btn-sm'>Edit</a>
                        <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                      </td>";
              echo "</tr>";
            }
          }
          ?>
        </tbody>
      </table>

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


      <!-- images-Table -->
      <h4>Images</h4>
      <table id="images-table" class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">User Name</th>
            <th scope="col">Album Name</th>
            <th scope="col">image</th>
            <th scope="col">Status</th>
            <th scope="col">Access Type</th>
            <th scope="col" style="width: 200px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $conn = mysqli_connect("localhost", "root", "", "photogallery");
          $sql = "SELECT images.*, users.first_name, users.last_name, albums.name AS album_name
                  FROM images
                  JOIN users ON images.user_id = users.id
                  JOIN albums ON images.album_id = albums.id
                  ORDER BY images.created_at DESC
                  LIMIT 3";

          require_once '../connection.php';

          $result = mysqli_query($conn, $sql);

          mysqli_close($conn);

          if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $checked = $row['status'] == 'active' ? 'checked' : '';
              $userName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
              $albumName = htmlspecialchars($row['album_name']);
              echo "<tr>";
              echo "<th scope='row'>" . $row['id'] . "</th>";
              echo "<td>" . $userName . "</td>";
              echo "<td>" . $albumName . "</td>";
              echo "<td><img src='../uploaded_images/" . htmlspecialchars($row['image']) . "' width='50' height='50'></td>";
              echo "<td class='status-col'>
              <div class='form-check form-switch'>
                <input class='form-check-input status-switch' type='checkbox' role='switch' data-id='" . $row['id'] . "' $checked>
                <label class='form-check-label'></label>
              </div>
            </td>";
              echo "<td class='image-attributes'>" . htmlspecialchars($row['access_type']) . "</td>";
              echo "<td>
                  <a href='../uploaded_images/" . htmlspecialchars($row['image']) . "' class='btn btn-success btn-sm fancybox' data-fancybox='gallery' data-caption='" . htmlspecialchars($row['album_name']) . "'>View</a>
                  <a href='image/edit_image.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>
                  <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                </td>";
              echo "</tr>";
            }
          }
          ?>
        </tbody>
      </table>

      <?php require_once 'footer.php'; ?>
    </div>
  </div>
</div>

<!-- Carousel Modal -->
<div id="carouselModal" class="modal fade" tabindex="-1" aria-labelledby="carouselModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="carouselModalLabel">Carousel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="carouselExampleControls" class="carousel slide">
          <div class="carousel-inner">
            <!-- Carousel items will be dynamically added here -->
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

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
    document.querySelectorAll('.status-switch').forEach(switchElement => {
      switchElement.addEventListener('change', function() {
        const id = this.getAttribute('data-id');
        const isChecked = this.checked;
        const status = isChecked ? 'active' : 'inactive';
        const row = this.closest('tr');

        // Update status in the database
        fetch(`update_status.php?id=${id}&status=${status}`, {
            method: 'GET'
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Hide or show all columns except the status and action columns
              row.querySelectorAll('td').forEach(cell => {
                // Skip the status and action columns
                if (cell.classList.contains('status-col') || cell.classList.contains('action-col')) {
                  cell.style.visibility = 'visible'; // Ensure status and action columns remain visible
                } else {
                  cell.style.visibility = isChecked ? 'visible' : 'hidden';
                }
              });
            } else {
              alert('Error updating status.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
          });
      });
    });
  });
</script>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    let imagesId;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function() {
        imagesId = this.getAttribute('data-id');
        deleteModal.show();
      });
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
      fetch(`delete_image.php?id=${imagesId}`, {
          method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Error deleting images.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
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


</html>