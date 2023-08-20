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

$sql = "SELECT * FROM airports";
$result = $conn->query($sql);

$airports = array();
while ($row = $result->fetch_assoc()) {
    $airports[] = $row;
}

header('Content-Type: application/json');
echo json_encode($airports);

$conn->close();
?>