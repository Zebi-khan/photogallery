<?php
require_once '../config.php';  // Ensure this is included to get the $conn variable

$errors = [];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize input
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $access_type = mysqli_real_escape_string($conn, $_POST['access_type']);
    $created_at = mysqli_real_escape_string($conn, $_POST['created_at']);
    $updated_at = mysqli_real_escape_string($conn, $_POST['updated_at']);

    // Handle the image upload
    $image = $_FILES['image']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // SQL query to insert the form data into the database
        $sql = "INSERT INTO images (first_name, last_name, image, status, access_type, created_at, updated_at)
                VALUES ('$first_name', '$last_name', '$image', '$status', '$access_type', '$created_at', '$updated_at')";

        require_once '../connection.php';

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            $success_message = "Data stored in the database successfully. Please browse your localhost phpMyAdmin to view the updated data.";
        } else {
            $errors[] = "Error: " . mysqli_error($conn);
        }
    } else {
        $errors[] = "Error uploading the image.";
    }

    // Close the database connection
    mysqli_close($conn);
}

// Redirect back to create.php with success or error messages
session_start();
if (!empty($errors)) {
    $_SESSION['error_message'] = $errors;
} else {
    $_SESSION['success_message'] = $success_message;
}

header("Location: image.php");
exit();
?>