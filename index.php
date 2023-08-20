<!DOCTYPE html>
<html lang="en">
<?php $page_name = 'Home'?>
<head><?php include './includes/header.php'; ?></head>

<body class="vh-100 bg-dark" data-bs-theme="dark">
<?php include './includes/navbar.php'; ?>
    <div class="container my-3 ">
        <div class="row ">
            <div class="col-md-3 mb-3">
                <div class="card ">
                    <div class="card-header ">Statistics</div>
                    <ul class="list-group list-group-flush ">
                        <li class="list-group-item ">Total Sightings: <span id="totalSightings">Loading...</span></li>
                        <li class="list-group-item ">Total Airports: <span id="totalAirports">Loading...</span></li>
                        <li class="list-group-item ">Total Aircrafts: <span id="totalTailNumbers">Loading...</span></li>
                        <li class="list-group-item ">Total Callsign: <span id="totalCallsigns">Loading...</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card ">
                    <div class="card-header ">Air Crafts</div>
                </div>
            </div>
        </div>
    </div>

    <script src="./assets/scripts/index.js"></script>
</body>

</html>