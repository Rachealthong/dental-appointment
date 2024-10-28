<?php
session_start();
?>

<nav>
    <img src="../Assets/Minimalist Dental Teeth Medical Clinic Logo (1).png" height="80px" alt="Bright Smiles Dental">
    <ul>
        <li><a href="manage_booking_dentist.php">Manage Booking</a></li>
        <li><a href="update_availability.php">Update Availability</a></li>
    </ul> 
    <?php 
    if (isset($_SESSION['dentist_id'])){
        $email = $_SESSION['email'];
        echo "<div>
            <a href='../Dentist Management/userprofile.php'>$email &nbsp;</a>
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