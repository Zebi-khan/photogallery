<?php
if (isset($_GET['id'])) {
    $album_id = $_GET['id'];
    $conn = mysqli_connect("localhost", "root", "", "photogallery");

    if ($conn) {
        $sql = "DELETE FROM albums WHERE id = $album_id";

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
    echo json_encode(['success' => false, 'error' => 'Invalid album ID']);
}
?>