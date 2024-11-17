<!-- Sidebar -->
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-2 p-0">
      <nav class="sidebar sidebar-border p-3">
        <a href="/admin/index.php" class="text-dark fs-3 ms-3 text-decoration-none">Dashboard</a>
        <ul class="nav flex-column mt-5">
          <?php
          // Get the current file name
          $currentPage = basename($_SERVER['PHP_SELF']);
          ?>
          <li class="nav-item mb-5">
            <a class="nav-link text-dark <?= ($currentPage == 'manage.php' && strpos($_SERVER['REQUEST_URI'], 'album') !== false) ? 'active' : '' ?>" href="/admin/album/manage.php">Manage Albums</a>
          </li>
          <li class="nav-item mb-5">
            <a class="nav-link text-dark <?= ($currentPage == 'manage.php' && strpos($_SERVER['REQUEST_URI'], 'image') !== false) ? 'active' : '' ?>" href="/admin/image/manage.php">Manage Gallery</a>
          </li>
          <li class="nav-item mb-5">
            <a class="nav-link text-dark <?= ($currentPage == 'manage.php' && strpos($_SERVER['REQUEST_URI'], 'user') !== false) ? 'active' : '' ?>" href="/admin/user/manage.php">Manage Users</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark <?= ($currentPage == 'settings.php') ? 'active' : '' ?>" href="/admin/settings.php">Settings</a>
          </li>
        </ul>
      </nav>
    </div>