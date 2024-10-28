<?php
session_start();
include '../dbconnect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Patient's Biodata</title>
<meta charset="utf-8">
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
    <div class="container">
    <h1>Patient's Profile</h1>
    </div>
    <div class="container">
    <?php if (isset($_SESSION['dentist_id'])): 
        $patient_id = $_GET['patient_id'] ?? '';

        // Retrieve appointments for the logged-in dentist
        $sql = "SELECT * FROM patients WHERE patient_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $patient_data = $result->fetch_assoc();
    ?>

    <?php if ($patient_data): ?>
        Name: <?php echo $patient_data['patient_name']; ?><br>
        Email: <?php echo $patient_data['patient_email']; ?><br>
        Phone: <?php echo $patient_data['patient_phoneno']; ?><br>
        Gender: <?php echo $patient_data['patient_gender']; ?><br>
        Nationality: <?php echo $patient_data['patient_nationality']; ?><br>
        Date of Birth: <?php echo $patient_data['patient_dob']; ?><br>
    </div>
    <div class="container">
    <?php
    $sql = "SELECT a.appointment_id, d.dentist_name, s.service_type, 
            sch.available_date, sch.available_time, a.remarks, a.cancelled, a.rescheduled
            FROM appointments a
            JOIN schedule sch ON a.schedule_id = sch.schedule_id
            JOIN dentists d ON sch.dentist_id = d.dentist_id
            JOIN services s ON a.service_id = s.service_id
            WHERE a.patient_id = ? 
            ORDER BY sch.available_date DESC, sch.available_time DESC";  


    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);  // Bind the patient ID to the query
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <h2>Appointment Booking History</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Dentist's Name</th>
                <th>Service</th>
                <th>Appointment Date</th>
                <th>Appointment Time</th>
                <th>Remarks</th>
                <th>Cancelled</th>
                <th>Rescheduled</th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($row['dentist_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['available_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['available_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                    <td><?php echo $row['cancelled'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $row['rescheduled'] ? 'Yes' : 'No'; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php else: ?>
        <p>No patient found.</p>
    <?php endif; ?>

    <?php else: ?>
        <h2>Please Log In using Dentist Credentials</h2>
        <p>You need to log in as Dentist to manage dental appointments. <a href="../User Registration/login.html">Log in here</a>.</p>
    <?php endif; ?>
    </div>
    <div id="footer"></div>
</div>
<script>
    fetch('navbar_dentist.php')
        .then(response => response.text())
        .then(data => document.getElementById('navbar').innerHTML = data);
    fetch('footer_dentist.html')
    .then(response => response.text())
    .then(data => document.getElementById('footer').innerHTML = data);
</script>
</body>
</html>