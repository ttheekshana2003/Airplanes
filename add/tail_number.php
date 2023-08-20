<?php
session_start();
if (isset($_SESSION['call_sign'])) {
    $call_sign = $_SESSION['call_sign'];
    
} else {
    $_SESSION['message'] = 'Callsign Missing.';
    $_SESSION['code'] = '400';
    die( header( 'location: ../error'));
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tail_number = $_POST['tail_number'];
    $_SESSION['tail_number'] = $tail_number;
    $status = "";

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
    $sql_check_callsign = "SELECT COUNT(*) as count FROM tail_numbers WHERE tail_number = '$tail_number'";
    $result_check_callsign = $conn->query($sql_check_callsign);
    $count_callsign = $result_check_callsign->fetch_assoc()['count'];

    if ($count_callsign > 0) {
        $status = "Tail number already exists in the database.";
        header("Location: add_sighting");
        exit;
    } else {
        // Use the API to fetch flight information based on the call sign
        $api = "https://api.adsbdb.com/v0/aircraft/" . urlencode($tail_number);
        $api_response = file_get_contents($api);
        $reg_info = json_decode($api_response, true);

        if ($reg_info && isset($reg_info['response']['aircraft'])) {
            $registration = $reg_info['response']['aircraft']['registration'];
            $type = $reg_info['response']['aircraft']['type'];
            $icao_type = $reg_info['response']['aircraft']['icao_type'];
            $manufacturer = $reg_info['response']['aircraft']['manufacturer'];
            $mode_s = $reg_info['response']['aircraft']['mode_s'];
            $owner = $reg_info['response']['aircraft']['registered_owner'];
            $country = $reg_info['response']['aircraft']['registered_owner_country_name'];
            $country_iso_name = $reg_info['response']['aircraft']['registered_owner_country_iso_name'];
            

            // Insert callsign data into the "callsigns" table
            $sql_insert_callsign = "INSERT INTO tail_numbers (tail_number, mode_s, type, icao_type, manufacturer, owner, country, country_iso_name) VALUES ('$registration','$mode_s', '$type', '$icao_type', '$manufacturer', '$owner', '$country', '$country_iso_name')";
            $conn->query($sql_insert_callsign);
            $status = "Data inserted successfully.";
            
            header("Location: add_sighting");
            exit;
        } else {
            $status = "Failed to retrieve flight information from the API.";
        }
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<?php $page_name = 'Enter the tail number';
     include '../includes/header.php'; ?>
</head>
</head>
<body class="vh-100 overflow-hidden bg-dark" data-bs-theme="dark">
<?php include '../includes/navbar.php'; ?>
    <h2>Add Tail Number</h2>
    <form autocomplete="off" method="post" action="tail_number">

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