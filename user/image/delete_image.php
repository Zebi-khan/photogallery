<?php
require_once '/wamp64/www/photogallery/config.php';
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image_id = $_POST['id'];

    // Delete the album from the database
    $sql = "DELETE FROM images WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $image_id);

    if ($stmt->execute()) {
        echo "Image deleted successfully";
    } else {
        http_response_code(500);
        echo "Failed to delete image";
    }

    $stmt->close();
    $conn->close();
}
?>