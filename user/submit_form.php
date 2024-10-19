<?php
require_once '../config.php';  // Ensure this is included to get the $conn variable

$errors = [];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize input
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $access_type = mysqli_real_escape_string($conn, $_POST['access_type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $created_at = mysqli_real_escape_string($conn, $_POST['created_at']);
    $updated_at = mysqli_real_escape_string($conn, $_POST['updated_at']);

    // Handle the thumbnail upload
    $thumbnail = $_FILES['thumbnail']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($thumbnail);

    if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)) {
        // SQL query to insert the form data into the database
        $sql = "INSERT INTO album (first_name, last_name, slug, thumbnail, status, access_type, description, created_at, updated_at)
                VALUES ('$first_name', '$last_name', '$slug', '$thumbnail', '$status', '$access_type', '$description', '$created_at', '$updated_at')";

        // Execute the query

        require_once '../connection.php';

        if (mysqli_query($conn, $sql)) {
            $success_message = "Data stored in the database successfully. Please browse your localhost phpMyAdmin to view the updated data.";
        } else {
            $errors[] = "Error: " . mysqli_error($conn);
        }
    } else {
        $errors[] = "Error uploading the thumbnail.";
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

header("Location: create.php");
exit();
?>