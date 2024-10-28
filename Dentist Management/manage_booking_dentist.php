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
                    a.appointment_id,
                    p.patient_id,
                    p.patient_name, 
                    s.service_type,
                    sch.available_date, 
                    sch.available_time, 
                    a.remarks,
                    a.cancelled,
                    a.rescheduled
                FROM 
                    appointments a
                JOIN 
                    Patients p ON a.patient_id = p.patient_id
                JOIN
                    Services s ON a.service_id = s.service_id
                JOIN 
                    Schedule sch ON a.schedule_id = sch.schedule_id
                WHERE 
                    a.dentist_id = ? 
                ORDER BY 
                    sch.available_date, sch.available_time";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $dentist_id);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>
        <div id="aboutus">
            <img id="aboutus_img" class="img_filter" src="../Assets/requestappointment.webp">
            <div class="bottom_centered"><h1>Manage Booking</h1></div>
        </div>
        <div class="search-container">
            <div>
                Select type of appointment to view: <br>
                <label><input type="radio" name="filter" value="upcoming" checked> Upcoming</label>
                <label><input type="radio" name="filter" value="past"> Past</label>
                <label><input type="radio" name="filter" value="cancelled-rescheduled"> Cancelled/Rescheduled</label>
            </div>
        </div>
        <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <form id="reschedule_form" method="post" action="reschedule_dentist.php">
            <table id="appointmentsTable">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Patient's Name</th>
                        <th>Service</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Remarks</th>
                        <th>Cancelled or Rescheduled</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th><input type="text" class="search-input" placeholder="Search Patient"></th>
                        <th><input type="text" class="search-input" placeholder="Search Service"></th>
                        <th><input type="text" class="search-input" placeholder="Search Date"></th>
                        <th><input type="text" class="search-input" placeholder="Search Time"></th>
                        <th><input type="text" class="search-input" placeholder="Search Remarks"></th>
                        <th><input type="text" class="search-input" placeholder="Search Cancelled/Rescheduled"></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $current_date = date('Y-m-d');
                    while ($row = $result->fetch_assoc()): 
                        $is_upcoming = $row['available_date'] >= $current_date;
                        $is_cancelled_or_rescheduled = $row['cancelled'] || $row['rescheduled'];
                        $is_selectable = $is_upcoming && !$row['cancelled'] && !$row['rescheduled'];
                ?>
                <tr class="<?php echo $is_cancelled_or_rescheduled ? 'cancelled-rescheduled' : ($is_upcoming ? 'upcoming' : 'past'); ?>">
                    <td>
                        <?php if ($is_selectable): ?>
                            <input type="radio" name="selected_appointment" value="<?php echo htmlspecialchars($row['appointment_id']); ?>" required>
                        <?php endif; ?>
                    </td>
                    <td><a href="patient_bio.php?patient_id=<?php echo htmlspecialchars($row['patient_id']); ?>"><?php echo htmlspecialchars($row['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['available_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['available_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                    <td><?php echo $is_cancelled_or_rescheduled ? ($row['cancelled'] ? 'Cancelled' : 'Rescheduled') : ''; ?></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <button class='button' type="submit">Reschedule</button>
            </form>
        <?php else: ?>
            <p>No appointments found.</p>
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

    document.querySelectorAll('.search-input').forEach(input => {
        input.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const columnIndex = Array.from(this.parentElement.parentElement.children).indexOf(this.parentElement);
            const rows = document.querySelectorAll('#appointmentsTable tbody tr');

            rows.forEach(row => {
                const cell = row.children[columnIndex];
                const match = cell.textContent.toLowerCase().includes(filter);
                row.style.display = match ? '' : 'none';
            });
        });
    });

    document.querySelectorAll('input[name="filter"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const filter = this.value;
            const rows = document.querySelectorAll('#appointmentsTable tbody tr');

            rows.forEach(row => {
                if (filter === 'upcoming' && row.classList.contains('upcoming')) {
                    row.style.display = '';
                } else if (filter === 'past' && row.classList.contains('past')) {
                    row.style.display = '';
                } else if (filter === 'cancelled-rescheduled' && row.classList.contains('cancelled-rescheduled')) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // Trigger the change event on page load to apply the default filter
    document.querySelector('input[name="filter"]:checked').dispatchEvent(new Event('change'));
</script>
</body>
</html>