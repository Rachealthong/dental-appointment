<?php
session_start();
include '..\dbconnect.php';

$appointment_id = $_GET['appointment_id'];

// Fetch appointment details for email
$sql = "SELECT a.appointment_id, d.dentist_name, s.service_type, 
               sch.available_date, sch.available_time, a.remarks, p.patient_email 
        FROM appointments a
        JOIN schedule sch ON a.schedule_id = sch.schedule_id
        JOIN dentists d ON sch.dentist_id = d.dentist_id
        JOIN services s ON a.service_id = s.service_id
        JOIN patients p ON a.patient_id = p.patient_id
        WHERE a.appointment_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cancellation Confirmation</title>
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
            <h2>Appointment Cancelled Successfully</h2>
            <p>An email confirmation has been sent to your registered email address.<br> The details of cancelled appointment is listed below:</p>
            <?php if ($appointment): ?>
                <div class="appointment_details">
                    <ul style="list-style-type: none; padding: 0;">
                        <li><strong>Appointment ID:</strong> <?php echo htmlspecialchars($appointment['appointment_id']); ?></li>
                        <li><strong>Dentist:</strong> <?php echo htmlspecialchars($appointment['dentist_name']); ?></li>
                        <li><strong>Service:</strong> <?php echo htmlspecialchars($appointment['service_type']); ?></li>
                        <li><strong>Date:</strong> <?php echo htmlspecialchars($appointment['available_date']); ?></li>
                        <li><strong>Time:</strong> <?php echo htmlspecialchars($appointment['available_time']); ?></li>
                        <li><strong>Remarks:</strong> <?php echo htmlspecialchars($appointment['remarks']); ?></li>
                    </ul>
                </div>
            <?php endif; ?>
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