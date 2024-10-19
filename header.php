<?php
require_once 'config.php'; // Include the configuration file

// Define a list of non-admin pages where admins should be redirected to the admin dashboard
$non_admin_pages = ['login.php', 'home.php', 'user_page.php']; // Add any other pages where admins should not access

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Check if the current page is a non-admin page and if the user is an admin
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] && in_array($current_page, $non_admin_pages)) {
    // Redirect admins away from non-admin pages to the admin dashboard
    header("Location: /admin/index.php");
    exit();
}

// Ensure user session is active and admin session is inactive
if (isset($_SESSION['role']) && $_SESSION['role'] == 'user') {
  $_SESSION['user_logged_in'] = true;
  $_SESSION['admin_logged_in'] = false;
} else {
  // Redirect to login if not a user
  // header("Location: /login.php");
  // exit();
}
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="/assets/images/favicon.png" type="image/x-icon">
  <title>Photo Gallery - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../../assets/css/style.css">

  <script>
    function generateSlug() {
      let albumName = document.getElementById('name').value;
      let slug = albumName.trim().toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
      document.getElementById('slug').value = slug;
    }
  </script>

</head>

<body>
  <header class="py-3">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <img src="/assets/images/logo.png" alt="image" height="70">
        </div>
        <div class="col-lg-8 d-flex align-items-center justify-content-end">
          <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav custom-nav">
                  <li class="nav-item">
                    <a class="nav-link custom-link me-4" href="/">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link me-4" href="#">About</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link me-4" href="/user/album/albums.php">Albums</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link me-4" href="/user/image/gallery.php">Gallery</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link me-5" href="#">Contact Us</a>
                  </li>

                  <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                      <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img style="border-radius: 50%; width: 40px; height: 40px;" height="43" src="/assets/images/profile.png" alt="Profile" width="40">
                        <b class="ms-2"><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'user_name'; ?></b>
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="/user/albums.php">My Albums</a></li>
                        <li><a class="dropdown-item" href="/user/gallery.php">My Galleries</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="/logout.php">Logout</a></li>
                      </ul>
                    </li>
                  <?php else: ?>
                    <div class="login">
                      <a href="/login.php" class="btn btn-light custom-btn rounded-0 pe-5 ps-5">Login</a>
                    </div>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </header>