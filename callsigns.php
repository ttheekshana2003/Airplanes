<!DOCTYPE html>
<html lang="en">

<head>
<?php $page_name = 'Callsigns'?>
<head><?php include './includes/header.php'; ?></head>
    <!-- Include Bootstrap CSS for dark mode -->


</head>

<body class="vh-100 bg-dark" data-bs-theme="dark">
    <?php include 'includes/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4">Flight Information</h1>
        <table class="table table-dark table-bordered">
            <thead>
                <tr>
                    <th>Call Sign</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Airline</th>
                </tr>
            </thead>
            <tbody id="flightTableBody">
                <!-- JSON data will be dynamically inserted here -->
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS for responsive behavior -->
        <script>
        // Fetch JSON data from the API
        fetch('./api/callsigns.php')
            .then(response => response.json())
            .then(jsonData => {
                var tbody = document.getElementById('flightTableBody');
                jsonData.forEach(function(flight) {
                    var row = document.createElement('tr');
                    row.innerHTML = `
            <td>${flight.call_sign}</td>
            <td>${flight.origin}</td>
            <td>${flight.destination}</td>
            <td>${flight.airline}</td>
          `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
</body>

</html>