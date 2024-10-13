<?php
// Start the session
session_start();

// Get appointment details from URL parameters
$dentist = $_GET['dentist'] ?? 'Unknown Dentist';
$service = $_GET['service'] ?? 'Unknown Service';
$date = $_GET['date'] ?? 'Unknown Date';
$time = $_GET['time'] ?? 'Unknown Time';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
<div id="wrapper">
    <header>
        <div id="navbar"></div>
    </header>
    
    <div id="booking_confirmation">
    <h2>Appointment booked successfully!</h2>
    <p>Your booking details are as follow:</p>
    <ul>
        <li><strong>Dentist:</strong> <?php echo htmlspecialchars($dentist); ?></li>
        <li><strong>Service:</strong> <?php echo htmlspecialchars($service); ?></li>
        <li><strong>Date:</strong> <?php echo htmlspecialchars($date); ?></li>
        <li><strong>Time:</strong> <?php echo htmlspecialchars($time); ?></li>
    </ul>
    <p>We have sent you an email for confirmation.</p>
    </div>

    <div id="footer"></div>
</div>
<script>
    fetch('../Elements/navbar.php')
        .then(response => response.text())
        .then(data => document.getElementById('navbar').innerHTML = data);
    fetch('../Elements/footer.html')
    .then(response => response.text())
    .then(data => document.getElementById('footer').innerHTML = data);
</script>
</body>
</html>
