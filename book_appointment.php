<?php
//session_start();

// Check if the user is logged in
//$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true; 

//because havent set up the login system yet, so we will hardcode it to true
$is_logged_in = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
    <div id="navbar"></div>
    </header>
    <main>
        <?php if ($is_logged_in): ?>
            <h2>Request an Appointment</h2>
            <form method="post" action="submit_appointment.php">
                <label for="dentist">Request an Appointment with: </label>
                <select id="dentist" name="dentist" required>
                    <option value="eunice_seng">Dr Eunice Seng</option>
                    <option value="thong_peiyu">Dr Thong Peiyu</option>
                    <option value="ali_abu">Dr Ali Abu Bin Akau</option>
                </select>
                <br><br>
                <label for="service">Choose a Service: </label>
                <select id="service" name="service" required>
                    <option value="service1">Service 1</option>
                    <option value="service2">Service 2</option>
                    <option value="service3">Service 3</option>
                </select>
                <br><br>
                <label for="date">Preferred Date:</label>
                <input type="date" id="date" name="date" required>
                <br><br>
                <label for="time">Preferred Time:</label>
                <input type="time" id="time" name="time" required>
                <br><br>
                <label for="remarks">Remarks:</label>
                <textarea id="remarks" name="remarks" rows="4" cols="50"></textarea>
                <br><br>
                <button type="reset">Clear</button>
                <button type="submit">Submit</button>
            </form>
        <?php else: ?>
            <h2>Please Log In</h2>
            <p>You need to log in to book an appointment. <a href="login.php">Log in here</a>.</p>
        <?php endif; ?>
    </main>
    <div id="footer"></div>
</div>
<script>
    fetch('Elements/navbar.html')
        .then(response => response.text())
        .then(data => document.getElementById('navbar').innerHTML = data);
    fetch('Elements/footer.html')
    .then(response => response.text())
    .then(data => document.getElementById('footer').innerHTML = data);
</script>
</body>
</html>