
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
                    a.rescheduled,
                    a.attendance
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
        
            
        <div id="tabs">
            <button class="tablink active" onclick="openTab(event, 'upcoming')">Upcoming Appointments</button>
            <button class="tablink" onclick="openTab(event, 'past')">Past Appointments</button>
            <button class="tablink" onclick="openTab(event, 'cancelled')">Cancelled/Rescheduled Appointments</button>
        </div>
        
        <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <form id="reschedule_form" method="post" action="reschedule_dentist.php">
            <table id="appointmentsTable">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Patient's Name</th>
                        <th>Service</th>
                        <th>Remarks</th>
                        <th>Cancelled or Rescheduled</th>
                        <th id="attendanceHeader" style="display: none;">Attendance</th> <!-- Attendance column header, initially hidden -->
                    </tr>
                    <tr>
                        <th></th>
                        <th><input type="text" class="search-input" placeholder="Search Date"></th>
                        <th><input type="text" class="search-input" placeholder="Search Time"></th>
                        <th><input type="text" class="search-input" placeholder="Search Patient"></th>
                        <th><input type="text" class="search-input" placeholder="Search Service"></th>
                        <th><input type="text" class="search-input" placeholder="Search Remarks"></th>
                        <th><input type="text" class="search-input" placeholder="Search Cancelled/Rescheduled"></th>
                        <th id="attendanceSearchHeader" style="display: none;"><input type="text" class="search-input" placeholder="Search Attendance"></th> <!-- Attendance search input column, initially hidden -->
                    </tr>
                </thead>
                <tbody>
                <?php 
                    date_default_timezone_set('Asia/Singapore');
                    $current_date = date('Y-m-d');
                    $current_time = date("H:i:s");


                    while ($row = $result->fetch_assoc()): 
                        $appointment_date = $row['available_date'];
                        $appointment_time = $row['available_time'];

                    
                        // Determine if the appointment is upcoming
                        if ($appointment_date > $current_date) {
                            $is_upcoming = true; // Appointment is in a future date
                        } elseif ($appointment_date == $current_date && $appointment_time > $current_time) {
                            $is_upcoming = true; // Appointment is today, but still in the future
                        } else {
                            $is_upcoming = false; // Appointment is either in the past or earlier today
                        }
                    
                        $is_cancelled_or_rescheduled = $row['cancelled'] || $row['rescheduled'];
                        $is_selectable = !$row['cancelled'] && !$row['rescheduled'];
                    
                ?>
                <tr class="<?php echo $is_cancelled_or_rescheduled ? 'cancelled-rescheduled' : ($is_upcoming ? 'upcoming' : 'past'); ?>">
                    <td>
                        <?php if ($is_selectable): ?>
                            <input type="radio" name="selected_appointment" value="<?php echo htmlspecialchars($row['appointment_id']); ?>" required>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['available_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['available_time']); ?></td>
                    <td><a href="patient_bio.php?patient_id=<?php echo htmlspecialchars($row['patient_id']); ?>"><?php echo htmlspecialchars($row['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                    <td><?php echo $is_cancelled_or_rescheduled ? ($row['cancelled'] ? 'Cancelled' : 'Rescheduled') : ''; ?></td>
                    <td class="attendanceCell" style="display: none;">
                        <?php if (!$is_upcoming): ?>
                            <select name="attendance[<?php echo htmlspecialchars($row['appointment_id']); ?>]">
                                <option value="" <?php echo (is_null($row['attendance']) ? 'selected' : ''); ?>>Select</option>
                                <option value="1" <?php echo ($row['attendance'] == 1 ? 'selected' : ''); ?>>Attended</option>
                                <option value="0" <?php echo ($row['attendance'] === 0 ? 'selected' : ''); ?>>No Show</option>
                            </select>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <br>
            <div class="button_centered">
            <button class='button' type="submit">Reschedule</button>
            </div>
             <!-- Update Attendance Button -->
             <div id="updateAttendanceButton" style="display: none; align-items: center;" class="button_centered">
                <button class="button" type="submit" formaction="update_attendance.php" formmethod="post">Update Attendance</button>
            </div>
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

    // Search filter functionality
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

    function openTab(event, filter) {
        const rows = document.querySelectorAll('#appointmentsTable tbody tr');
        const isUpcomingTab = filter === 'upcoming';
        const isSelectable = filter === 'upcoming' || filter === 'past';
        
        // Show or hide rows based on selected tab
        rows.forEach(row => {
            if (isUpcomingTab && row.classList.contains('upcoming')) {
                row.style.display = '';
            } else if (!isUpcomingTab && filter === 'past' && row.classList.contains('past')) {
                row.style.display = '';
            } else if (!isUpcomingTab && filter === 'cancelled' && row.classList.contains('cancelled-rescheduled')) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Show or hide the "Select" column in header, search row, and body based on the "Upcoming" tab
        const selectHeader = document.querySelector('#appointmentsTable thead tr:nth-child(1) th:nth-child(1)');
        const selectSearchHeader = document.querySelector('#appointmentsTable thead tr:nth-child(2) th:nth-child(1)');
        const selectCells = document.querySelectorAll('#appointmentsTable tbody td:nth-child(1)');
        
        // Toggle visibility of the "Select" column header, search input column, and cells
        if (selectHeader) {
            selectHeader.style.display = isSelectable ? '' : 'none';
        }
        if (selectSearchHeader) {
            selectSearchHeader.style.display = isSelectable ? '' : 'none';
        }
        selectCells.forEach(cell => {
            cell.style.display = isSelectable ? '' : 'none';
        });

        // Enable or disable the reschedule button
        const rescheduleButton = document.querySelector('#reschedule_form button[type="submit"]');
        if (rescheduleButton) {
            rescheduleButton.style.display = isUpcomingTab ? '' : 'none';
        }

        // Show the attendance header and cells only for Past Appointments tab
        const attendanceHeader = document.getElementById('attendanceHeader');
        const attendanceCells = document.querySelectorAll('.attendanceCell');
        const updateAttendanceButton = document.getElementById('updateAttendanceButton');
        const attendanceSearchHeader = document.getElementById('attendanceSearchHeader');

        if (filter === 'past') {
            attendanceHeader.style.display = 'table-cell';
            attendanceSearchHeader.style.display = 'table-cell';
            attendanceCells.forEach(cell => cell.style.display = 'table-cell');
            updateAttendanceButton.style.display = 'flex';
        } else {
            attendanceHeader.style.display = 'none';
            attendanceSearchHeader.style.display = 'none';
            attendanceCells.forEach(cell => cell.style.display = 'none');
            updateAttendanceButton.style.display = 'none';
        }

        // Remove active class from all buttons and set it to the clicked button
        document.querySelectorAll('.tablink').forEach(tab => tab.classList.remove('active'));
        event.currentTarget.classList.add('active');
    }

    // Set default tab to "Upcoming Appointments" on page load
    document.addEventListener('DOMContentLoaded', function() {
        openTab({ currentTarget: document.querySelector('.tablink.active') }, 'upcoming');
    });

</script>
</body>
</html>