<?php require_once '../header.php'; ?>
<?php require_once '../sidebar.php'; ?>

<div class="col-lg-10">
  <div class="container">
    <div class="row mt-4 ps-2">
      <div class="col-lg-12 p-0 d-flex justify-content-between align-items-center border-bottom pb-3">
        <h3>Users</h3>
        <a href="add.php"><button type="button" class="btn btn-primary rounded-0 pe-3 ps-3">Add user</button></a>
      </div>
    </div>
  </div>

  <!-- Start of the user table -->
  <table class="table custom-table table-bordered mt-4 ms-2">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">First Name</th>
        <th scope="col">Last Name</th>
        <th scope="col">Email</th>
        <th scope="col">Phone</th>
        <th scope="col">Date Of Birth</th>
        <th scope="col">Gender</th>
        <th scope="col" style="width: 200px;">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Example of dynamically generating rows
      $conn = mysqli_connect("localhost", "root", "", "photogallery");
      $sql = "SELECT * FROM users";

      require_once '../../connection.php';

      $result = mysqli_query($conn, $sql);

      mysqli_close($conn);

      while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<th scope="row">' . $row['id'] . '</th>';
        echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
        echo '<td>' . htmlspecialchars($row['date_of_birth']) . '</td>';
        echo '<td>' . htmlspecialchars($row['gender']) . '</td>';
        echo '<td>';
        echo '<a href="#" class="text-decoration-none pe-3">View</a>';
        echo '<a href="#" class="text-decoration-none pe-3">Edit</a>';
        echo '<a href="#" class="text-decoration-none delete-btn" data-id="' . $row['id'] . '">Delete</a>';
        echo '</td>';
        echo '</tr>';
      }
      ?>
    </tbody>
  </table>
  <!-- End of the user table -->

  <?php require_once '../footer.php'; ?>
</div>
</div>
</div>

<?php require_once '../assets/js/modal.php'; ?>