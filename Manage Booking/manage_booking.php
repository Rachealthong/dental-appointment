<?php
session_start();
include '..\dbconnect.php';
$is_logged_in = isset($_SESSION['patient_id']);
$patient_id = $_SESSION['patient_id'];  // Assuming patient ID is stored in session

// Prepare the SQL query to fetch appointment data
// Prepare the SQL query to fetch appointment data
$sql = "SELECT a.appointment_id, d.dentist_name, s.service_type, 
               sch.available_date, sch.available_time, a.remarks 
        FROM appointments a
        JOIN schedule sch ON a.schedule_id = sch.schedule_id
        JOIN dentists d ON sch.dentist_id = d.dentist_id
        JOIN services s ON a.service_id = s.service_id
        WHERE a.patient_id = ? AND a.cancelled = 0 AND a.rescheduled = 0  AND sch.available_date > NOW()
        ORDER BY sch.available_date DESC, sch.available_time DESC
        ";  


// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);  // Bind the patient ID to the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data
$appointment = $result->fetch_assoc();


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
            <div>
            <?php if ($appointment): ?>
                <form id="reschedule" method="post">
                <h2>Upcoming Booking</h2>
                <ul>
                    <li>Appointment ID: <?php echo htmlspecialchars($appointment['appointment_id']); ?></li>
                    <li>Dentist:<?php echo htmlspecialchars($appointment['dentist_name']); ?></li>
                    <li>Service: <?php echo htmlspecialchars($appointment['service_type']); ?></li>
                    <li>Date:<?php echo htmlspecialchars($appointment['available_date']); ?></li>
                    <li>Time:<?php echo htmlspecialchars($appointment['available_time']); ?></li>
                    <li>Remarks:<?php echo htmlspecialchars($appointment['remarks']); ?></li>
                </ul>
                <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">
                <button type="submit" name="action" value="cancel" onclick="setFormAction('cancel.php')">Cancel Appointment </button>
                <button type="submit" name="action" value="reschedule" onclick="setFormAction('reschedule.php')">Reschedule Appointment</button>
                </form>
                <?php else: ?>
                    <h2>No Upcoming Booking</h2>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <h2>Please Log In</h2>
            <p>You need to log in to manage booked appointments. <a href="../User Registration/login.html">Log in here</a>.</p>
        <?php endif; ?>
    </main>
    <div id="footer"></div>
</div>

<script>
    fetch('../Elements/navbar.php')
        .then(response => response.text())
        .then(data => document.getElementById('navbar').innerHTML = data);
    fetch('../Elements/footer.html')
        .then(response => response.text())
        .then(data => document.getElementById('footer').innerHTML = data);

      
    function setFormAction(action) {
        document.getElementById('reschedule').action = action;
    }
</script>

</body>
</html>