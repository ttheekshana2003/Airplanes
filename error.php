<?php
session_start();

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} else {
    $message = 'Page not found'; 
}
if (isset($_SESSION['code'])) {
    $code = $_SESSION['code'];
    unset($_SESSION['code']);
} else {
    $code = '404'; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $page_name = 'Error ' .$code?>
<head><?php include './includes/header.php'; ?></head>

<body class="vh-100 bg-dark" data-bs-theme="dark">
    <style>
        body {
            background-image: url('./images/404.jpg');
            background-blend-mode: multiply;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
    <div class="position-relative" style="height: 100%;">
    <?php include 'includes/navbar.php'; ?>

        <div class="position-absolute top-50 start-50 translate-middle  align-middle">
            <center>
                <h1><?php echo $code;?></h1>
            </center>
            <h2 style="font-family: Airborne 86 Stencil;"><?php echo $message;?></p></h2>
        </div>
    </div>
</body>

</html>