<?php
//session_start();

// Check if the user is logged in
//$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true; 

//because havent set up the login system yet, so we will hardcode it to true
$is_logged_in = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointment</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<div id="wrapper">
    <header>
    <div id="navbar"></div>
    </header>
    <main>
        <?php if ($is_logged_in): ?>
            <div id="aboutus">
                <img id="aboutus_img" class="img_filter" src="Assets/requestappointment.webp">
                <div class="bottom_centered"><h1>Manage Booking</h1></div>
            </div>
            <p>Havent do. Need to write if else statement for if user=patient and if user=dentist then 
                display different things.</p>
            </p>
        <?php else: ?>
            <h2>Please Log In</h2>
            <p>You need to log in to book an appointment. <a href="login.php">Log in here</a>.</p>
        <?php endif; ?>
    </main>
    <div id="footer"></div>
</div>

<script>
    fetch('Elements/navbar.html')
        .then(response => response.text())
        .then(data => document.getElementById('navbar').innerHTML = data);
    fetch('Elements/footer.html')
        .then(response => response.text())
        .then(data => document.getElementById('footer').innerHTML = data);
</script>
</body>
</html>