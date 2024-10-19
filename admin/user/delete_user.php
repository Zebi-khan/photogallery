<?php
// delete_user.php
if (isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    
    // Establish database connection
    $conn = mysqli_connect("localhost", "root", "", "photogallery");
    if ($conn === false) {
        die("Error: Could not connect" . mysqli_connect_error());
    }

    // Delete the user from the database
    $sql = "DELETE FROM users WHERE id = $user_id";
    
    require_once '../../connection.php';

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error";
    }

    mysqli_close($conn);
}
?>