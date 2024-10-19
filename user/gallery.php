<?php
require_once '../config.php';

// Establish a database connection
$conn = mysqli_connect("localhost", "root", "", "photogallery");
if ($conn === false) {
    die("Error: Could not connect to database.");
}

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /login.php");
    exit();
}

require_once '../header.php';

// Fetch images from the database
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$sql = "SELECT id, image, created_at 
        FROM images 
        WHERE user_id = '$user_id'"; // Fetch only images uploaded by this user

require_once '../connection.php';

$result = mysqli_query($conn, $sql);

mysqli_close($conn);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}
?>

<div class="gallery py-5">
  <div class="container">
    <div class="mb-5 d-flex justify-content-end">
      <a href="image.php" class="btn btn-primary rounded-0 ps-3 pe-3">Add image</a>
    </div>
    <div class="row">
      <?php
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $image = htmlspecialchars($row['image']);
          echo "
              <div class='col-3 mb-4'>
                  <img src='../uploaded_images/{$image}' class='img-fluid equal-height'>
              </div>";
        }
      } else {
        echo "<p>No images found. Start adding images to your gallery!</p>";
      }
      ?>
    </div>
  </div>
</div>

<?php
require_once '../footer.php';
?>