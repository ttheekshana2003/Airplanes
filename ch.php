<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "plane_spotting";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT owner, COUNT(*) AS aircraft_count
          FROM tail_numbers
          GROUP BY owner
          ORDER BY aircraft_count DESC";
$result = $conn->query($query);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();

// Sort the data in descending order by aircraft_count
usort($data, function ($a, $b) {
    return $b['aircraft_count'] - $a['aircraft_count'];
});

// Keep the top 5 airlines, and group the rest as "Other"
$topAirlines = array_slice($data, 0, 5);
$otherCount = count($data) - 5;
$otherSum = array_sum(array_slice($data, 5));

$topAirlines[] = array("owner" => "Other", "aircraft_count" => $otherSum);

$data_json = json_encode($topAirlines);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./packages/chart.js"></script>
    <title>Document</title>
</head>
<body>
<canvas id="donutChart" width="400" height="400"></canvas>


<script>
var chartData = <?php echo $data_json; ?>;

var labels = chartData.map(item => item.owner);
var counts = chartData.map(item => item.aircraft_count);

var ctx = document.getElementById("donutChart").getContext("2d");
var myChart = new Chart(ctx, {
    type: "doughnut",
    data: {
        labels: labels,
        datasets: [
            {
                data: counts,
                backgroundColor: [
                    "#FF5733", "#36A2EB", "#FFC300", /* ... */
                ],
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
    },
});
</script>


</body>
</html>