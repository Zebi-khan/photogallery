<?php
require_once '/wamp64/www/photogallery/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $album_id = $_POST['id'];

    // Delete the album from the database
    $sql = "DELETE FROM albums WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $album_id);

    if ($stmt->execute()) {
        echo "Album deleted successfully";
    } else {
        http_response_code(500);
        echo "Failed to delete album";
    }

    $stmt->close();
    $conn->close();
}
?>