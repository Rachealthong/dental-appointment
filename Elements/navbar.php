<?php
session_start();
?>

<nav>
    <img src="../Assets/Minimalist Dental Teeth Medical Clinic Logo (1).png" height="80px" alt="Bright Smiles Dental">
    <ul>
        <li><a href="../Others/index.html">Home</a></li>
        <li><a href="../Dentists/dentists.html">Dentists</a></li>
        <li><a href="../Services/services.html">Services</a></li>
        <li><a href="../Booking Appointment/book_appointment.php">Book Appointment</a></li>
        <li><a href="../Manage Booking/manage_booking.php">Manage Booking</a></li>
        <li><a href="../Others/contact.html">Contact</a></li>
    </ul> 
    <?php 
    if (isset($_SESSION['patient_id'])){
        $email = $_SESSION['email'];
        echo "<div>
            <a href='../User Profile/userprofile.php'>$email &nbsp;</a>
            </div>";
        echo "<div id='login_nav'>
            <a href='../User Registration/logout.php'>Log Out</a>
            </div>";
    }
    else{
        echo "<div id='login_nav'>
            <a href='../User Registration/login.html'>Log In</a>
            </div>";
    }
    ?>
</nav>