<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "plane_spotting";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to count aircrafts by owner
$sql = "SELECT owner, COUNT(*) AS aircraft_count
        FROM tail_numbers
        GROUP BY owner
        ORDER BY aircraft_count DESC";

$result = $conn->query($sql);

// Fetch the results into an associative array
$aircraftCounts = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $aircraftCounts[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<?php $page_name = 'Airlines'?>
<head><?php include 'includes/header.php'; ?></head>
<body class="vh-100 bg-dark" data-bs-theme="dark">
<?php include 'includes/navbar.php'; ?>
    <div class="container my-3" style="padding-top: 15px">
        <div class="row">
        <?php foreach ($aircraftCounts as $aircraft) { ?>
            <div class="col-md-3 mb-4">
                        <div class="card" style="width: 15rem;">
                            <img src="assets/images/Airlines/big/<?php echo $aircraft['owner']; ?>.png" class="card-img-top" alt="<?php echo $aircraft['owner']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $aircraft['owner']; ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">Toatal aircrafts: <?php echo $aircraft['aircraft_count']; ?></h6>                       
                            </div>
                        </div>
                    </div>
        <?php } ?>
        <style>
            .card-img-top{
                
            }
        </style>
</body>
</html>
