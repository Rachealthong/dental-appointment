<?php
include '../dbconnect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Manage Booking</title>
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
    <?php if (isset($_SESSION['dentist_id'])): 
        $dentist_id = $_SESSION['dentist_id'];

        // Retrieve appointments for the logged-in dentist
        $sql = "SELECT 
                    p.patient_name, 
                    s.available_date, 
                    s.available_time, 
                    a.remarks
                FROM 
                    appointments a
                JOIN 
                    Patients p ON a.patient_id = p.patient_id
                JOIN 
                    Schedule s ON a.schedule_id = s.schedule_id
                WHERE 
                    a.dentist_id = ?
                ORDER BY 
                    s.available_date, s.available_time";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $dentist_id);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>
        <div id="aboutus">
            <img id="aboutus_img" class="img_filter" src="../Assets/requestappointment.webp">
            <div class="bottom_centered"><h1>Manage Booking</h1></div>
        </div>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient ID</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['available_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['available_time']); ?></td>
                            <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>
    <?php else: ?>
        <h2>Please Log In using Dentist Credentials</h2>
        <p>You need to log in as Dentist to manage dental appointments. <a href="../User Registration/login.html">Log in here</a>.</p>
    <?php endif; ?>
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