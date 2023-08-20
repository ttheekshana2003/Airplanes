<!DOCTYPE html>
<html lang="en">


<?php $page_name = 'Airports'?>
<head><?php include './includes/header.php'; ?>
    <link rel="stylesheet" href="./assets/packages/leaflet/leaflet.css">
    <script src="./assets/packages/leaflet/leaflet.js"></script>
</head>

<body class="vh-100 bg-dark" data-bs-theme="dark">
    <?php include './includes/navbar.php'; ?>

    <style>
        #map {
            width: 100%;
            height: 300px;
        }
    </style>
    <div class="container my-3">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card " style="width: 30rem">
                    <div class="card " style="width: 100%; ">
                        <div class="card-header ">All Airports</div>
                        <ul class="list-group list-group-flush " id="airportsList">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9 mb-2">
                <div class="card " style="width: 30vw; margin-left: 14rem;">
                    <div class="card-body">
                        <h5 class="card-title">Airports</h5>
                        <div id="map"></div>
                    </div>
                </div>
            </div>

        </div>

        <script src="./assets/scripts/airports.js"></script>
        <script src=".assets/packages/bootstrap-5.3.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>