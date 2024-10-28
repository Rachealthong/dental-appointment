<?php
session_start();
include '../dbconnect.php'; // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['selected_appointment'])) {
        $appointment_id = $_POST['selected_appointment'];
        $dentist_id = $_SESSION['dentist_id'];

        // Fetch the existing appointment details
        $sql = "SELECT a.appointment_id, p.patient_name, d.dentist_name, s.service_type, 
                       sch.available_date, sch.available_time, a.remarks 
                FROM appointments a
                JOIN dentists d ON a.dentist_id = d.dentist_id
                JOIN schedule sch ON a.schedule_id = sch.schedule_id
                JOIN patients p ON a.patient_id = p.patient_id
                JOIN services s ON a.service_id = s.service_id
                WHERE a.appointment_id = ? AND a.dentist_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $appointment_id, $dentist_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();
    } else {
        // Redirect back to the manage booking page with an error message
        echo "<script type='text/javascript'>
        alert('No appointment selected.');
        window.location.href = 'manage_booking_dentist.php';
        </script>";
        exit;
    }
} else {
    // Redirect back to the manage booking page if accessed directly
    header('Location: manage_booking_dentist.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Reschedule Booking</title>
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
        <div id="aboutus">
            <img id="aboutus_img" class="img_filter" src="../Assets/requestappointment.webp">
            <div class="bottom_centered"><h1>Reschedule an Appointment</h1></div>
        </div>
        <div class="container">
        <?php if ($appointment): ?> 
            <form id="reschedule_form" method="post" action="../Manage Booking/submit_reschedule_appointment.php">
            <label for="patient">Patient's Name: </label>
            <input type="text" id="patient" name="patient" value="<?php echo htmlspecialchars($appointment['patient_name']); ?>" readonly>
            <br>
            <label for="dentist">Requested an Appointment with: </label>
            <input type="text" id="dentist" name="dentist" value="<?php echo htmlspecialchars($appointment['dentist_name']); ?>" readonly>
            <br>
            <label for="service">Chosen Service: </label>
            <input type="text" id="service" name="service" value="<?php echo htmlspecialchars($appointment['service_type']); ?>" readonly>
            <br>
            <label for="date">Select New Date:</label>
            <p><strong>Original Date:</strong> <?php echo htmlspecialchars($appointment['available_date']); ?></p>
            <?php
            $minDate = date('Y-m-d', strtotime('+1 day'));
            $maxDate = date('Y-m-d', strtotime('+14 days')); // two weeks from today
            ?>
            <input type="date" id="date" name="date" min="<?php echo $minDate; ?>" max="<?php echo $maxDate; ?>" required>
            <br>
            <label for="time">Select New Time:</label>
            <p><strong>Original Time:</strong> <?php echo htmlspecialchars($appointment['available_time']); ?></p>
            <select id="time" name="time" required>
                <option value="" disabled selected>Select a time</option>
            </select>
            <br>

            <label for="remarks">Remarks:</label>
            <textarea id="remarks" name="remarks" rows="4" cols="50" style="width: 100%;"><?php echo htmlspecialchars($appointment['remarks']); ?></textarea>
            <br>
            <div id="reset_submit">
                <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">
                <button type="reset">Clear</button>
                <button type="submit">Submit</button>
            </div>
        </form>
        <?php else: ?>
            <p>No appointments found.</p>
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

    document.addEventListener('DOMContentLoaded', function() {
    const dentist = document.getElementById('dentist').value;
    const service = document.getElementById('service').value;

    if (dentist && service) {
        fetchAvailableSlots(dentist, service);
    }
    });

    function fetchAvailableSlots(dentist, service) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `../Manage Booking/fetch_available_slots.php?dentist=${encodeURIComponent(dentist)}&service=${encodeURIComponent(service)}`, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = xhr.responseText;
                if (response.startsWith('error:')) {
                    console.error(response.substring(6));
                    return;
                }
                const slots = response.split(';');
                updateDateAndTimeSelects(slots);
            }
        };
        xhr.send();
    }

    function updateDateAndTimeSelects(slots) {
        const dateSelect = document.getElementById('date');
        const timeSelect = document.getElementById('time');

        // Clear previous options
        dateSelect.innerHTML = '<option value="" disabled selected>Select a date</option>';
        timeSelect.innerHTML = '<option value="" disabled selected>Select a time</option>';

        const timeMap = {};

        slots.forEach(slot => {
            const [date, time] = slot.split(',');
            if (!timeMap[date]) {
                timeMap[date] = [];
                dateSelect.innerHTML += `<option value="${date}">${date}</option>`;
            }
            timeMap[date].push(time);
        });

        dateSelect.addEventListener('change', function() {
            const selectedDate = this.value;
            timeSelect.innerHTML = '<option value="" disabled selected>Select a time</option>';
            if (timeMap[selectedDate]) {
                timeMap[selectedDate].forEach(time => {
                    timeSelect.innerHTML += `<option value="${time}">${time}</option>`;
                });
            }
        });
    }

</script>
</body>
</html>