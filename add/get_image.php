<?php
// Database connection setup and other code...

// Get tail number from form submission
$tailNumber = isset($_POST['tail_number']) ? $_POST['tail_number'] : '';

if ($tailNumber !== '') {
    // Fetch thumbnail image data from API
    $apiUrl = "https://www.airport-data.com/api/ac_thumb.json?r=$tailNumber";
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if (isset($data['data'][0]['image'])) {
        $thumbnailUrl = $data['data'][0]['image'];

        // Modify thumbnail URL to get full image URL
        $fullImageUrl = str_replace(['cdn.', 'thumbnails/'], '', $thumbnailUrl);

        // Save thumbnail and full image to specified directories
        $thumbnailPath = "../images/Airframes/thumbnails/$tailNumber.jpg";
        $fullImagePath = "../images/Airframes/$tailNumber.jpg";

        file_put_contents($thumbnailPath, file_get_contents($thumbnailUrl));
        file_put_contents($fullImagePath, file_get_contents($fullImageUrl));

        $status = "Images saved successfully!";
    } else {
        $status = "Thumbnail image not found from API response.";
    }
} else {
    $status = "Tail number is missing.";
}

// Close database connection and other code...
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tail Number</title>
    <link rel="stylesheet" href="../stylesheets/styles.css">
    <link href="../bootstrap-5.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="../bootstrap-5.3.1/js/bootstrap.bundle.min.js"></script>
	<link rel="icon" type="image/png" href="./images/favicon_48x48.png">
</head>
<body>
    <h2>Add Tail Number</h2>
    <form autocomplete="off" method="post" action="get_image.php">
        <div class="mb-3">
        <label for="type" class="form-label">Enter the Tail number:</label>
        <input type="text" name="tail_number" class="form-control">
        </div>

        <input  class="btn btn-primary" type="submit" value="Next">
        <div id="statusContainer"></div>
    </form>
    

    <script>
        var status = "<?php echo $status; ?>"; // Get the PHP-generated content

        var statusContainer = document.getElementById("statusContainer");
        var statusElement = document.createElement("p");
        statusElement.className = "status";
        statusElement.textContent = status;

        statusContainer.appendChild(statusElement);
    </script>
</body>
</html>