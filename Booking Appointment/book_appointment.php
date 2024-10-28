<?php
session_start();
include('../dbconnect.php');

// Check if the user is logged in
$is_logged_in = isset($_SESSION['patient_id']);
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
        <?php if ($is_logged_in): ?>
            <div id="aboutus">
                <img id="aboutus_img" class="img_filter" src="../Assets/requestappointment.webp">
                <div class="bottom_centered"><h1>Request an Appointment</h1></div>
            </div> 
            <div id="appointment_form">
            <form id="appointment" method="post" action="submit_appointment.php">
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
                <label for="date">Preferred Date:</label>
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
                    <button type="reset">Clear</button>
                    <button type="submit">Submit</button>
                </div>
            </form>
            </div>
        <?php else: ?>
            <div class="plslogin">
            <h2>Please Log In</h2>
            <p>You need to log in to book an appointment. <a href="../User Registration/login.html">Log in here</a>.</p>
            </div>
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
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');

    // Clear previous options
    timeSelect.innerHTML = '<option value="" disabled selected>Select a time</option>';

    const timeMap = {};

    slots.forEach(slot => {
        const [date, time] = slot.split(',');
        if (!timeMap[date]) {
            timeMap[date] = [];
        }
        timeMap[date].push(time);
    });

    // Update available time slots when a date is selected
    dateInput.addEventListener('change', function() {
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