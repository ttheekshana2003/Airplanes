<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $call_sign = $_POST['call_sign'];
    $_SESSION['call_sign'] = $call_sign;
    $status = "Enter the Callsign";

    // Database connection
    $servername = "localhost";
    $username = "root"; // Default username for XAMPP
    $password = "";     // Default password for XAMPP
    $dbname = "plane_spotting";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the callsign is already in the database
    $sql_check_callsign = "SELECT COUNT(*) as count FROM callsigns WHERE call_sign = '$call_sign'";
    $result_check_callsign = $conn->query($sql_check_callsign);
    $count_callsign = $result_check_callsign->fetch_assoc()['count'];

    if ($count_callsign > 0) {
        
        $status = "Callsign already exists in the database.";
        header("Location: tail_number");
        exit;
        
    } else {
        // Use the API to fetch flight information based on the call sign
        $api_url = "https://api.adsbdb.com/v0/callsign/" . urlencode($call_sign);
        $status = "getting data about". $call_sign;
        $api_response = file_get_contents($api_url);
        $flight_info = json_decode($api_response, true);

        if ($flight_info && isset($flight_info['response']['flightroute'])) {
            $airline_name = $flight_info['response']['flightroute']['airline']['name'];
            $origin_iata = $flight_info['response']['flightroute']['origin']['iata_code'];
            $destination_iata = $flight_info['response']['flightroute']['destination']['iata_code'];
            $origin_data = $flight_info['response']['flightroute']['origin'];
            $destination_data = $flight_info['response']['flightroute']['destination'];

            // Insert callsign data into the "callsigns" table
            $sql_insert_callsign = "INSERT INTO callsigns (call_sign, origin, destination, airline) VALUES ('$call_sign','$origin_iata', '$destination_iata', '$airline_name')";
            $conn->query($sql_insert_callsign);

            insertAirport($conn, $origin_data);
            insertAirport($conn, $destination_data);
            
            $status = "Callsign added to th database successfully.";
            header("Location: tail_number");
            exit;
                     
        } else {
            $status = "Failed to retrieve flight information from the API.";
        }
    }
    
    $conn->close();
}
function insertAirport($conn, $airport_data) {
    $iata_code = $airport_data['iata_code'];
    $icao_code = $airport_data['icao_code'];
    $name = $airport_data['name'];
    $country = $airport_data['country_name'];
    $country_iso_name = $airport_data['country_iso_name'];    
    $latitude = $airport_data['latitude'];
    $longitude = $airport_data['longitude'];
    $elevation = $airport_data['elevation'];

    // Check if the airport already exists in the database
    $sql_check_airport = "SELECT COUNT(*) as count FROM airports WHERE iata_code = '$iata_code'";
    $result_check_airport = $conn->query($sql_check_airport);
    $count_airport = $result_check_airport->fetch_assoc()['count'];

    if ($count_airport == 0) {
        // Insert airport data into "airports" table
        $sql_insert_airport = "INSERT INTO airports (iata_code, icao_code, name, country, country_iso_name, longitude, latitude, elevation) VALUES ('$iata_code', '$icao_code', '$name', '$country', '$country_iso_name', '$longitude', '$latitude', '$elevation')";
        $conn->query($sql_insert_airport);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<?php $page_name = 'Enter the callsign';
     include '../includes/header.php'; ?>
</head>
<body class="vh-100 overflow-hidden bg-dark" data-bs-theme="dark">
<?php include '../includes/navbar.php'; ?>
    <h2>Add Callsign</h2>
    <form autocomplete="off" method="post" action="callsign">

        <div class="mb-3">
            <label for="type" class="form-label">Enter the callsign:</label>
            <input type="text" name="call_sign" class="form-control">
        </div>    

        <input class="btn btn-primary" type="submit" value="Next">
        <div id="statusContainer"></div>
    </form>
    

    <script>
        var status = "<?php echo $status; ?>"; 

        var statusContainer = document.getElementById("statusContainer");
        var statusElement = document.createElement("p");
        statusElement.className = "status";
        statusElement.textContent = status;

        statusContainer.appendChild(statusElement);
    </script>
</body>
</html>