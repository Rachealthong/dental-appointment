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
    <title>Book Appointment</title>
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
            <h2>Request an Appointment</h2>
            <form method="post" action="submit_appointment.php">
                <label for="dentist">Request an Appointment with: </label>
                <select id="dentist" name="dentist" required>
                    <option value="eunice_seng">Dr Eunice Seng</option>
                    <option value="thong_peiyu">Dr Thong Peiyu</option>
                    <option value="ali_abu">Dr Ali Abu Bin Akau</option>
                </select>
                <br><br>
                <label for="service">Choose a Service: </label>
                <select id="service" name="service" required>
                    <option value="service1">Service 1</option>
                    <option value="service2">Service 2</option>
                    <option value="service3">Service 3</option>
                </select>
                <br><br>
                <label for="date">Preferred Date:</label>
                <input type="date" id="date" name="date" required>
                <br><br>
                <label for="time">Preferred Time:</label>
                <input type="time" id="time" name="time" required>
                <br><br>
                <label for="remarks">Remarks:</label>
                <textarea id="remarks" name="remarks" rows="4" cols="50"></textarea>
                <br><br>
                <button type="reset">Clear</button>
                <button type="submit">Submit</button>
            </form>
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