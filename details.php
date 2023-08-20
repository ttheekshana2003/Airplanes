<!DOCTYPE html>
<html lang="en">

<head><meta charset="UTF-8">
<?php $page_name = $_GET['tail_number'];
      include './includes/header.php'; ?></head>

<body class="vh-100 bg-dark" data-bs-theme="dark">
    
<?php include 'includes/navbar.php'; ?>
    
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

                // Get aircraft parameters from the request
                $tailNumber = isset($_GET['tail_number']) ? $_GET['tail_number'] : '';

                if ($tailNumber !== '') {
                    // Prepare and execute SQL query
                    $query = "SELECT * FROM tail_numbers WHERE tail_number = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $tailNumber);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $aircraftData = $result->fetch_assoc();

                        // Display customized content
                        $tail_number= $aircraftData['tail_number'];
                        $mode_s = $aircraftData['mode_s'];
                        $type = $aircraftData['type'];
                        $icao_type = $aircraftData['icao_type'];
                        $manufacturer = $aircraftData['manufacturer'];
                        $owner = $aircraftData['owner'];
                        $country = $aircraftData['country'];
                        $country_iso = $aircraftData['country_iso_name'];

                        $query = "SELECT call_sign, time FROM sighting WHERE tail_number = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("s", $tail_number);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $trips = '';
                            while ($row = $result->fetch_assoc()) {
                                $callsign = $row['call_sign'];
                                $time = $row['time'];
                                $queryCallsign = "SELECT origin, destination FROM callsigns WHERE call_sign = ?";
                                $stmtCallsign = $conn->prepare($queryCallsign);
                                $stmtCallsign->bind_param("s", $callsign);
                                $stmtCallsign->execute();
                                $resultCallsign = $stmtCallsign->get_result();

                                if ($resultCallsign->num_rows > 0) {
                                    $callsignData = $resultCallsign->fetch_assoc();
                                    $originIata = $callsignData['origin'];
                                    $destinationIata = $callsignData['destination'];

                                    // Fetch origin airport details
                                    $queryOrigin = "SELECT name, country FROM airports WHERE iata_code = ?";
                                    $stmtOrigin = $conn->prepare($queryOrigin);
                                    $stmtOrigin->bind_param("s", $originIata);
                                    $stmtOrigin->execute();
                                    $resultOrigin = $stmtOrigin->get_result();

                                    if ($resultOrigin->num_rows > 0) {
                                        $originData = $resultOrigin->fetch_assoc();
                                        $originName = $originData['name'];
                                        $originCountry = $originData['country'];
                                    } else {
                                        $originName = "Unknown Airport";
                                        $originCountry = "";
                                    }

                                    // Fetch destination airport details
                                    $queryDestination = "SELECT name, country FROM airports WHERE iata_code = ?";
                                    $stmtDestination = $conn->prepare($queryDestination);
                                    $stmtDestination->bind_param("s", $destinationIata);
                                    $stmtDestination->execute();
                                    $resultDestination = $stmtDestination->get_result();

                                    if ($resultDestination->num_rows > 0) {
                                        $destinationData = $resultDestination->fetch_assoc();
                                        $destinationName = $destinationData['name'];
                                        $destinationCountry = $destinationData['country'];
                                    } else {
                                        $destinationName = "Unknown Airport";
                                        $destinationCountry = "";
                                    }

                                    $tripInfo = "Origin: $originName ($originCountry), Destination: $destinationName ($destinationCountry)";

                                    
                                    $trips .= "<li class='list-group-item' data-bs-toggle='tooltip' data-bs-placement='top' title='$callsign'><div class='accordion accordion-flush'><div class='accordion-item'>
                                    <h5 class='accordion-header
                                    <button class='accordion-button collapsed' href='#' data-bs-toggle='collapse' data-bs-target='#flush-collapse$callsign' aria-expanded='false' aria-controls='flush-collapse$callsign'>
                                    $callsign</h5><p class='card-subtitle mb-2 text-muted'>$time</p></button>
                                    <div id='flush-collapse$callsign' class='accordion-collapse collapse' data-bs-parent='#accordionFlush'>
                                    <div class='accordion-body'>
                                    <ul class='list-group list-group-flush '>
                                        <li class='list-group-item '>$originIata <p class='card-subtitle mb-2 text-muted'>$originName</p> <p class='card-subtitle mb-2 text-muted'>$originCountry</p></li>
                                        <li class='list-group-item '>$destinationIata <p class='card-subtitle mb-2 text-muted'>$destinationName</p> <p class='card-subtitle mb-2 text-muted'>$destinationCountry</p></li>
                                    </ul>
                                    </div>
                                    </div>
                                    </div>
                                </div></li>";
                                }
                            }
                        } else {
                            $trips = "<li class='list-group-item'>No trips recorded for this aircraft.</li>";
                }
                    } else {
                        $tail_number = $_GET['tail_number'];
                        $manufacturer = "";
                        $owner = "";
                        $country = "";
                        $icao_type = ""; 
                        $trips = "<p>Unknown aircraft</p>";
                    }

                    $stmt->close();
                } else {
                    $tail_number = "";
                    $manufacturer = "";
                    $owner = "";
                    $country = "";
                    $icao_type = ""; 
                    $trips = "<p>Parameter is missing</p>";
                    header("Location:aircrafts.php");
                }

                $conn->close();
            ?>
        <div class="container my-3">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card " style="width: 29rem; ">
                <img src="./assets/images/Airframes/<?php echo $tail_number; ?>.jpg" class="card-img-top" alt="<?php echo $tail_number; ?>" onerror="setDefaultImage(this)">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $tail_number; ?>                    
                        <img src="./assets/images/Airlines/<?php echo $owner; ?>.png" alt="<?php echo $owner; ?>"></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $manufacturer; ?> <?php echo $icao_type; ?></h6>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $owner; ?></h6> 
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $country; ?></h6>
                    </div>                
                </div>                           
            </div>
            <div class="col-md-9 mb-2">
                <div class="card " style="width: 26rem; margin-left: 18rem;">
                    <div class="card-body">
                    <h5 class="card-title">Trips</h5>
                    <ul class="list-group list-group-flush">
                    <?php echo $trips; ?>
            </ul>                    
                </div>              
        </div>        
    </div> 
    <script>function setDefaultImage(image) {
        image.onerror = null; // Prevent infinite loop if default image also fails
        image.src = "./assets/images/Airframes/default.jpg"; // Path to your default image
    }
</script>
<style>
    .accordion-button{
        cursor: pointer;
    }
</style>   
</body>
</html>
