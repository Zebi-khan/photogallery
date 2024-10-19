<?php
require_once '/wamp64/www/photogallery/config.php';
require_once '/wamp64/www/photogallery/header.php';

// Fetch image details based on the image ID
if (isset($_GET['id'])) {
    $image_id = $_GET['id'];

    // Fetch image details
    $image_sql = "SELECT image, created_at FROM images WHERE id = '$image_id' AND access_type = 'public'";

    require_once '../../connection.php';

    $image_result = mysqli_query($conn, $image_sql);

    mysqli_close($conn);

    if (!$image_result || mysqli_num_rows($image_result) == 0) {
        echo "<p>Image not found or access is restricted.</p>";
        exit;
    }

    $image = mysqli_fetch_assoc($image_result);

} else {
    echo "Invalid image ID.";
    exit;
}

?>

<div class="container mt-4">
    <h2 class="text-center">Image Details</h2>
    
    <div class="row">
        <div class="col-12 text-center">
            <img src="../../uploaded_images/<?= htmlspecialchars($image['image']); ?>" class="img-fluid" style="max-height: 300px; object-fit: cover;">
            <div class="mt-3">
                <p><strong>Created At:</strong> <?= date('Y-m-d', strtotime($image['created_at'])); ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once '/wamp64/www/photogallery/footer.php'; ?>