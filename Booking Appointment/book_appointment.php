<?php
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['patient_id']);

$servername = "localhost"; // Update with your server details
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "dentalclinic"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
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
                <select id="date" name="date" required>
                    <option value="" disabled selected>Select a date</option>
                </select>
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

    function fetchAvailableSlots(dentist, service) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `fetch_available_slots.php?dentist=${encodeURIComponent(dentist)}&service=${encodeURIComponent(service)}`, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const slots = JSON.parse(xhr.responseText);
                updateDateAndTimeSelects(slots);
            }
        };
        xhr.send();
    }

    function updateDateAndTimeSelects(slots) {
        // Get the date and time select elements
        const dateSelect = document.getElementById('date');
        const timeSelect = document.getElementById('time');

        // Clear previous options
        dateSelect.innerHTML = '<option value="" disabled selected>Select a date</option>';
        timeSelect.innerHTML = '<option value="" disabled selected>Select a time</option>';

        // Create a map to store available times for each date
        const timeMap = {};

        // Populate the date and time selects based on the fetched slots
        slots.forEach(slot => {
            // Add available date if not already added
            if (!timeMap[slot.available_date]) {
                timeMap[slot.available_date] = [];
                dateSelect.innerHTML += `<option value="${slot.available_date}">${slot.available_date}</option>`;
            }
            // Add available time for the corresponding date
            timeMap[slot.available_date].push(slot.available_time);
        });

        // Update time slots when the user selects a date
        dateSelect.addEventListener('change', function() {
            const selectedDate = this.value;
            timeSelect.innerHTML = '<option value="" disabled selected>Select a time</option>'; // Clear previous times
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