<?php
session_start();
include '..\dbconnect.php';

$is_logged_in = isset($_SESSION['patient_id']);

if ($is_logged_in) {
    $patient_id = $_SESSION['patient_id'];
// Prepare the SQL query to fetch appointment data
$sql = "SELECT a.appointment_id, d.dentist_name, s.service_type, s.service_image,
               sch.available_date, sch.available_time, a.remarks 
        FROM appointments a
        JOIN schedule sch ON a.schedule_id = sch.schedule_id
        JOIN dentists d ON sch.dentist_id = d.dentist_id
        JOIN services s ON a.service_id = s.service_id
        WHERE a.patient_id = ? AND a.cancelled = 0 AND a.rescheduled = 0 AND sch.available_date > NOW()
        ORDER BY sch.available_date DESC, sch.available_time DESC";  


// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);  // Bind the patient ID to the query
$stmt->execute();
$result = $stmt->get_result();

// Prepare the SQL query to fetch past appointments
$sql_past = "SELECT a.appointment_id, d.dentist_name, s.service_type, s.service_image,
                     sch.available_date, sch.available_time, a.remarks 
              FROM appointments a
              JOIN schedule sch ON a.schedule_id = sch.schedule_id
              JOIN dentists d ON sch.dentist_id = d.dentist_id
              JOIN services s ON a.service_id = s.service_id
              WHERE a.patient_id = ? AND a.cancelled = 0 AND a.rescheduled = 0 AND sch.available_date < NOW()  -- Ensure we get past appointments
              ORDER BY sch.available_date DESC, sch.available_time DESC";  // Order by date and time

// Prepare and execute the statement for past appointments
$stmt_past = $conn->prepare($sql_past);
$stmt_past->bind_param("i", $patient_id);  // Bind the patient ID to the query
$stmt_past->execute();
$result_past = $stmt_past->get_result();

// fetch cancelled appointments
$sql_cancel = "SELECT a.appointment_id, d.dentist_name, s.service_type, s.service_image,
                     sch.available_date, sch.available_time, a.remarks 
              FROM appointments a
              JOIN schedule sch ON a.schedule_id = sch.schedule_id
              JOIN dentists d ON sch.dentist_id = d.dentist_id
              JOIN services s ON a.service_id = s.service_id
              WHERE a.patient_id = ? AND a.cancelled = 1 AND a.rescheduled = 0
              ORDER BY sch.available_date DESC, sch.available_time DESC";  // Order by date and time

// Prepare and execute the statement for past appointments
$stmt_cancel = $conn->prepare($sql_cancel);
$stmt_cancel->bind_param("i", $patient_id);  // Bind the patient ID to the query
$stmt_cancel->execute();
$result_cancel = $stmt_cancel->get_result();


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointment</title>
    <link rel="stylesheet" href="../styles.css">
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
                <img id="aboutus_img" class="img_filter" src="../Assets/requestappointment.webp">
                <div class="bottom_centered"><h1>Manage Booking</h1></div>
            </div>
            <div id="tabs">
                <button class="tablink" onclick="openTab(event, 'upcoming')">Upcoming Appointments</button>
                <button class="tablink" onclick="openTab(event, 'past')">Past Appointments</button>
                <button class="tablink" onclick="openTab(event, 'cancelled')">Cancelled Appointments</button>
            </div>

            <div id="all_booking">
            <div id="upcoming" class="tabcontent">
            <h2>Upcoming Appointments</h2>
           
            <?php
                if ($result->num_rows > 0) {
                    while ($appointment = $result->fetch_assoc()) { 
                        ?>
                        <div class="edit_booking">
                            <div class="form_image">
                                <img src="../Assets/<?php echo htmlspecialchars($appointment['service_image']); ?>" alt="<?php echo htmlspecialchars($appointment['service_type']); ?>">
                            </div>
                            <form class="manage_form" id="appointment_<?php echo htmlspecialchars($appointment['appointment_id']); ?>" method="post">
                                <ul style="list-style-type: none; padding: 0;">
                                   
                                    <li><h3><?php echo htmlspecialchars($appointment['service_type']); ?></h3></li>
                                    <li><?php echo (new DateTime($appointment['available_date']))->format('d M Y, l'); ?></li>
                                    <li><?php echo (new DateTime($appointment['available_time']))->format('H:i'); ?></li>
                                    <li><?php echo htmlspecialchars($appointment['dentist_name']); ?></li>
                                    <li>Remarks: <?php echo htmlspecialchars($appointment['remarks']); ?></li>
                                </ul>
                                <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">
                                
                                <!-- Use JavaScript to dynamically set the form's action -->
                                <button type="button" class="tablink" onclick="confirmCancel('cancel.php', <?php echo htmlspecialchars($appointment['appointment_id']); ?>)">Cancel Appointment</button>
                                &nbsp;
                                <button type="button" class="tablink" onclick="setFormAction('reschedule.php', <?php echo htmlspecialchars($appointment['appointment_id']); ?>)">Reschedule Appointment</button>
                                </form>
                        </div>
                        <?php 
                    }
                } else {
                    echo "<p>No upcoming booking.</p>";
                }
                ?>
            </div>
            <div id="past" class="tabcontent" style="display:none;">
      
                <h2>Past Appointments</h2>
                    
                    <?php
                    if ($result_past->num_rows > 0) {
                        // Loop through and display each past appointment
                        while ($appointment = $result_past->fetch_assoc()) {
                            ?>
                            <div class="past_booking">
                            <div class="form_image">
                                <img src="../Assets/<?php echo htmlspecialchars($appointment['service_image']); ?>" alt="<?php echo htmlspecialchars($appointment['service_type']); ?>">
                            </div>
                            <ul style="list-style-type: none; padding: 0;">
                                <li><h3><?php echo htmlspecialchars($appointment['service_type']); ?></h3></li>
                                <li><?php echo (new DateTime($appointment['available_date']))->format('d M Y, l'); ?></li>
                                <li><?php echo (new DateTime($appointment['available_time']))->format('H:i'); ?></li>
                                <li><?php echo htmlspecialchars($appointment['dentist_name']); ?></li>
                                <li>Remarks: <?php echo htmlspecialchars($appointment['remarks']); ?></li>
                            </ul>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No past appointments found.</p>";
                    }
                    ?>
                </div>
                <div id="cancelled" class="tabcontent" style="display:none;">
                    <h2>Cancelled Appointments</h2>
                    
                    <?php
                    if ($result_cancel->num_rows > 0) {
                        // Loop through and display each past appointment
                        while ($appointment = $result_cancel->fetch_assoc()) {
                            ?>
                            <div class="past_booking">
                            <div class="form_image">
                                <img src="../Assets/<?php echo htmlspecialchars($appointment['service_image']); ?>" alt="<?php echo htmlspecialchars($appointment['service_type']); ?>">
                            </div>
                            <ul style="list-style-type: none; padding: 0;">
                                <li><h3><?php echo htmlspecialchars($appointment['service_type']); ?></h3></li>
                                <li><?php echo (new DateTime($appointment['available_date']))->format('d M Y, l'); ?></li>
                                <li><?php echo (new DateTime($appointment['available_time']))->format('H:i'); ?></li>
                                <li><?php echo htmlspecialchars($appointment['dentist_name']); ?></li>
                                <li>Remarks: <?php echo htmlspecialchars($appointment['remarks']); ?></li>
                            </ul>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No cancelled appointments found.</p>";
                    }
                    ?>
                </div>
             </div>    
              
           
        <?php else: ?>
            <div id="aboutus">
                <img id="aboutus_img" class="img_filter" src="../Assets/requestappointment.webp">
                <div class="bottom_centered"><h1>Manage Booking</h1></div>
            </div>
            <div class="plslogin">
            <h2>Please Log In</h2>
            <p>You need to log in to manage booked appointments.<br> <a href="../User Registration/login.html">Log in here</a>.</p>
            </div>
        <?php endif; ?>
    </main>
    <div id="footer"></div>
</div>

<script src="manage_booking.js"></script>
</body>
</html>