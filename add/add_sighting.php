<?php
session_start();
if (isset($_SESSION['call_sign'])) {
    $call_sign = $_SESSION['call_sign'];

    if (isset($_SESSION['tail_number'])) {
        $tail_number = $_SESSION['tail_number'];
        
    } else {
        $_SESSION['message'] = 'tail Number is Missing.';
        $_SESSION['code'] = '400';
        die( header( 'location: ../error'));
    }
    
} else {
    $_SESSION['message'] = 'Callsign Missing.';
    $_SESSION['code'] = '400';
    die( header( 'location: ../error'));
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tail_number = $_SESSION['tail_number'];
    $call_sign = $_SESSION['call_sign'];
    $time = $_POST['time'];
    $has_kml = isset($_POST['has_kml']) ? $_POST['has_kml'] : 0;
    
    unset($_SESSION['call_sign']);
    unset($_SESSION['tail_number']);
    

    // Database connection
    $servername = "127.0.0.1";
    $username = "root"; // Default username for XAMPP
    $password = "";     // Default password for XAMPP
    $dbname = "plane_spotting";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Process file upload (if "has_kml" is checked)
    if ($has_kml && isset($_FILES['kml_file'])) {
        $target_dir = "kml_files/";
        $target_file = $target_dir . basename($_FILES["kml_file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is a KML file
        if ($imageFileType != "kml") {
            echo "Only KML files are allowed.";
            $uploadOk = 0;
        }

        // Check if file was uploaded successfully
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["kml_file"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["kml_file"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        // No KML file selected
        $target_file = "none";
    }

    // Insert sighting data into "airplanes" table
    $sql_insert_sighting = "INSERT INTO sighting (tail_number, call_sign, time, kml_file) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert_sighting);
    $stmt->bind_param("ssss", $tail_number, $call_sign, $time, $target_file);

    if ($stmt->execute()) {
        echo "Sighting added successfully!";
        header("Location: callsign");
    } else {
        echo "Error adding sighting: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<?php $page_name = 'Enter the Date & time';
     include '../includes/header.php'; ?>
</head>
<body class="vh-100 overflow-hidden bg-dark" data-bs-theme="dark">
<?php include '../includes/navbar.php'; ?>
    <h2>Step 3: Add Sighting Details</h2>
    <form autocomplete="off" method="post" action="add_sighting" enctype="multipart/form-data">
        <!-- Sighting details inputs -->
        
        <div class="mb-3">
        <label for="time" class="form-label">Enter Time:</label>
        <input type="datetime-local" name="time"><br>
        </div>

        <div class="form-check">
        <input type="checkbox" name="has_kml" value="1" class="form-check-input" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">Have KML File</label><br>
        </div>
        <!-- File upload input for KML file -->
        <div class="mb-3">
        <label for="formFile" class="form-label">Select KML File</label>
        <input class="form-control" type="file" id="formFile" name="kml_file"><br>
        </div>          

        <input class="btn btn-primary" type="submit" value="Add Sighting">
    </form>
</body>
</html>
