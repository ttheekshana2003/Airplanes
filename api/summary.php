<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = "";     // Default password for XAMPP
$dbname = "plane_spotting";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch summary data from the database
$sql = "SELECT COUNT(*) as totalSightings FROM sighting";
$result = $conn->query($sql);
$totalSightings = $result->fetch_assoc()['totalSightings'];

$sql = "SELECT COUNT(*) as totalAirports FROM airports";
$result = $conn->query($sql);
$totalAirports = $result->fetch_assoc()['totalAirports'];

$sql = "SELECT COUNT(*) as totalTailNumbers FROM tail_numbers";
$result = $conn->query($sql);
$totalTailNumbers = $result->fetch_assoc()['totalTailNumbers'];

$sql = "SELECT COUNT(*) as totalCallsigns FROM callsigns";
$result = $conn->query($sql);
$totalCallsigns = $result->fetch_assoc()['totalCallsigns'];

$data = array(
    'totalSightings' => $totalSightings,
    'totalAirports' => $totalAirports,
    'totalTailNumbers' => $totalTailNumbers,
    'totalCallsigns' => $totalCallsigns
);

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
