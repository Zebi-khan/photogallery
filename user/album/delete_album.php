<?php
require_once '/wamp64/www/photogallery/config.php';
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $album_id = $_POST['id'];

        // Check if the album exists in the database
        $sql_check = "SELECT * FROM albums WHERE id = ?";
        $stmt_check = $conn->prepare($sql_check);
        if ($stmt_check === false) {
            die('Error preparing statement: ' . $conn->error);
        }
        $stmt_check->bind_param('i', $album_id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        
        if ($result->num_rows > 0) {
            // Album exists, proceed with deletion
            $sql = "DELETE FROM albums WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die('Error preparing delete statement: ' . $conn->error);
            }
            $stmt->bind_param('i', $album_id);
            if ($stmt->execute()) {
                echo "Album deleted successfully";
            } else {
                http_response_code(500);
                echo "Failed to delete album: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Album not found.";
        }
        $stmt_check->close();
    } else {
        echo "Invalid album ID.";
    }
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
