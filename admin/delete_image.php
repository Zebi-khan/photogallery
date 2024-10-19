<?php
if (isset($_GET['id'])) {
    $image_id = $_GET['id'];
    $conn = mysqli_connect("localhost", "root", "", "photogallery");

    if ($conn) {
        $sql = "DELETE FROM images WHERE id = $image_id";

        require_once '../connection.php';

        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }
        mysqli_close($conn);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid image ID']);
}
?>
