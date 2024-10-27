<?php
session_start();
include '..\dbconnect.php';

$patient_id = $_SESSION['patient_id'];  // Assuming patient ID is stored in session
$appointment_id = $_POST['appointment_id'];

$action = $_POST['action'];
// Fetch original appointment details
$sql = "SELECT a.appointment_id, d.dentist_name, s.service_type, 
sch.available_date, sch.available_time, a.remarks 
FROM appointments a
JOIN schedule sch ON a.schedule_id = sch.schedule_id
JOIN dentists d ON sch.dentist_id = d.dentist_id
JOIN services s ON a.service_id = s.service_id
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
    <title>Book Appointment</title>
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
    <main>
        <?php if ($patient_id): ?>
            <div id="aboutus">
                <img id="aboutus_img" class="img_filter" src="../Assets/requestappointment.webp">
                <div class="bottom_centered"><h1>Reschedule an Appointment</h1></div>
            </div> 
            <div id="appointment_details">
                <h3>Original Appointment Details</h3>
                <p><strong>Dentist:</strong> <?php echo htmlspecialchars($appointment['dentist_name']); ?></p>
                <p><strong>Service:</strong> <?php echo htmlspecialchars($appointment['service_type']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($appointment['available_date']); ?></p>
                <p><strong>Time:</strong> <?php echo htmlspecialchars($appointment['available_time']); ?></p>
                <p><strong>Remarks:</strong> <?php echo htmlspecialchars($appointment['remarks']); ?></p>
            </div>
            <div>
            <form id="reschedule2" method="post" action="submit_reschedule_appointment.php">
            <h3>New Appointment Details</h3>
                <label for="dentist">Request an Appointment with: </label>
                <select id="dentist" name="dentist" required>
                <option value="" disabled selected>Select a dentist</option>
                    <?php
                    // Fetch dentists from the database
                    $sql = "SELECT dentist_name FROM dentists";
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['dentist_name']) . "'>" . htmlspecialchars($row['dentist_name']) . "</option>";
                    }
                    ?>
                </select>

                <br>
                <label for="service">Choose a Service: </label>
                <select id="service" name="service" required>
                <option value="" disabled selected>Select a service</option>
                    <?php
                    // Fetch services from the database
                    $sql = "SELECT service_type FROM services";
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['service_type']) . "'>" . htmlspecialchars($row['service_type']) . "</option>";
                    }
                    ?>
                </select>
                <br>
                <?php
                $minDate = date('Y-m-d', strtotime('+1 day'));
                $maxDate = date('Y-m-d', strtotime('+14 days')); // two weeks from today
                ?>
                <input type="date" id="date" name="date" min="<?php echo $minDate; ?>" max="<?php echo $maxDate; ?>" required>
                <br>
                
                <label for="time">Preferred Time:</label>
                <select id="time" name="time" required>
                    <option value="" disabled selected>Select a time</option>
                </select>
                <br>
                <label for="remarks">Remarks:</label>
                <textarea id="remarks" name="remarks" rows="4" cols="50" style="width: 100%;"></textarea>
                <br>
                <div id="reset_submit">
                <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">
                    <button type="reset">Clear</button>
                    <button type="submit">Submit</button>
                </div>
            </form>
            </div>
        <?php else: ?>
            <h2>Please Log In</h2>
            <p>You need to log in to book an appointment. <a href="../User Registration/login.html">Log in here</a>.</p>
        <?php endif; ?>
    </main>
    <div id="footer"></div>
</div>
</div>
<script>
    fetch('../Elements/navbar.php')
        .then(response => response.text())
        .then(data => document.getElementById('navbar').innerHTML = data);
    fetch('../Elements/footer.html')
    .then(response => response.text())
    .then(data => document.getElementById('footer').innerHTML = data);

    document.getElementById('dentist').addEventListener('change', function() {
    const dentist = this.value;
    const service = document.getElementById('service').value;
    
    if (service) {
        fetchAvailableSlots(dentist, service);
    }
});

    document.getElementById('service').addEventListener('change', function() {
        const service = this.value;
        const dentist = document.getElementById('dentist').value;
        
        if (dentist) {
            fetchAvailableSlots(dentist, service);
        }
    });

    document.getElementById('date').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const dayOfWeek = selectedDate.getUTCDay();

    // If the day is Saturday (6) or Sunday (0), clear the date input
    if (dayOfWeek === 0 || dayOfWeek === 6) {
        alert("Weekends are not available. Please choose a weekday.");
        this.value = ''; // Clear the input value
    }
});

    function fetchAvailableSlots(dentist, service) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `fetch_available_slots.php?dentist=${encodeURIComponent(dentist)}&service=${encodeURIComponent(service)}`, true);
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