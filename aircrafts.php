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

// Fetch all aircraft data
$query = "SELECT * FROM tail_numbers";
$result = $conn->query($query);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $page_name = 'Aircrafts';
     include './includes/header.php'; ?>
<script src="/assets/scripts/aircrafts.js"></script></head>

<body class="vh-100 bg-dark" data-bs-theme="dark">
<?php include './includes/navbar.php'; ?>
    <div class="container my-3" style="padding-top: 15px">
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $tail_number = $row['tail_number'];
                    $manufacturer = $row['manufacturer'];
                    $icao_type = $row['icao_type'];
                    $owner = $row['owner'];
                    $country = $row['country'];
            ?>
                    <div class="col-md-3 mb-4">
                        <a href="/details?tail_number=<?php echo $tail_number; ?>" class="airframe_card">
                        <div class="card" style="height: 18rem;">
                            <img src="/assets/images/Airframes/thumbnails/<?php echo $tail_number; ?>.jpg" class="card-img-top" alt="<?php echo $tail_number; ?>" onerror="setDefaultImage(this)">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $tail_number; ?>
                                <span class="badge rounded-pill text-bg-primary"><?php echo $icao_type; ?></span></h5>
                                
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $owner; ?></h6> 
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $country; ?></h6>                       
                            </div>
                        </div></a>
                    </div>
            <?php
                }
            } else {
                echo "<p>No aircraft found.</p>";
            }
            ?>
        </div>
    </div>
    

</body>
</html>
