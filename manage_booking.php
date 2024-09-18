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
</head>
<body>
    <header>
        <nav>
            <img src="Assets/logo.jpg" width="60px" alt="Bright Smiles Dental"> &nbsp;
            <a href="index.html">Home</a> &nbsp;
            <a href="dentists.html">Dentists</a> &nbsp;
            <a href="services.html">Services</a> &nbsp;
            <a href="book_appointment.php">Book Appointment</a> &nbsp;
            <a href="manage_booking.php">Manage Booking</a> &nbsp;
            <a href="contact.html">Contact</a>
            <button id="login">Log In</button>
        </nav>
    </header>
    <main>
        <?php if ($is_logged_in): ?>
        <h2>Manage Appointment</h2>
            <p>Havent do. Need to write if else statement for if user=patient and if user=dentist then 
                display different things.</p>
            </p>
        <?php else: ?>
            <h2>Please Log In</h2>
            <p>You need to log in to book an appointment. <a href="login.php">Log in here</a>.</p>
        <?php endif; ?>
    </main>
    <footer>
        <small>
            <i>Copyright &copy; 2024 Bright Smiles Dental</i> &nbsp;
            <a href="index.html">Home</a> &nbsp;
            <a href="dentists.html">Dentists</a> &nbsp;
            <a href="services.html">Services</a> &nbsp;
            <a href="book_appointment.php">Book Appointment</a> &nbsp;
            <a href="manage_booking.php">Manage Booking</a> &nbsp;
            <a href="contact.html">Contact</a>
        </small>
    </footer>
</div>
</body>
</html>