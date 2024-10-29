<?php
session_start();

include '../dbconnect.php';

// Fetch all service types
$sql = "SELECT service_id, service_type FROM services";
$result = $conn->query($sql);

$sql2 = "SELECT dentist_id, dentist_name FROM dentists";
$result2 = $conn->query($sql2);
?>


<nav>
    <a href="../Others/index.html"><img src="../Assets/Minimalist Dental Teeth Medical Clinic Logo (1).png" height="80px" alt="Bright Smiles Dental"></a>
    <ul>
        <li><a href="../Others/index.html">Home</a></li>
        <li class = "dropdown">
        <a href="../Dentists/dentists.php" class="dropbtn">Dentists</a>
            <div class="dropdown-content">
            <?php
                if ($result2->num_rows > 0) {
                    while ($row = $result2->fetch_assoc()) {
                        echo '<a href="../Dentists/dentist_bio.php?dentist_id=' . htmlspecialchars($row['dentist_id']) . '">' . htmlspecialchars($row['dentist_name']) . '</a>';
                    }
                } else {
                    echo '<p>No services available</p>';
                }
            ?>
            </div>
        </li>
        <li class = "dropdown">
        <a href="../Services/services.php" class="dropbtn">Services</a>
            <div class="dropdown-content">
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<a href="../Services/services_detail.php?service_id=' . htmlspecialchars($row['service_id']) . '">' . htmlspecialchars($row['service_type']) . '</a>';
                    }
                } else {
                    echo '<p>No services available</p>';
                }
                ?>
            </div>
        </li>
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