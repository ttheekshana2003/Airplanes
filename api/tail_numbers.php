<?php
session_start();
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
$_SESSION['message'] = 'Access Denied';
$_SESSION['code'] = '403';
die( header( 'location: ../error'));
}
?>
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

$sql = "SELECT * FROM tail_numbers";
$result = $conn->query($sql);

$tail_numbers = array();
while ($row = $result->fetch_assoc()) {
    $tail_numbers[] = $row;
}

header('Content-Type: application/json');
echo json_encode($tail_numbers);

$conn->close();
?>