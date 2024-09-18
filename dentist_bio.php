<!DOCTYPE html>
<html lang="en">
<head>
<title>Dentist Biodata</title>
<meta charset="utf-8">
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div id="wrapper">
    <header>
    </header>
    <nav>
        <img src="Assets/logo.jpg" width="60px" alt="Bright Smiles Dental"> &nbsp;
        <a href="index.html">Home</a> &nbsp;
        <a href="dentists.html">Dentists</a> &nbsp;
        <a href="services.html">Services</a> &nbsp;
        <a href="book_appointment.php">Book Appointment</a> &nbsp;
        <a href="manage_booking.php">Manage Booking</a> &nbsp;
        <a href="contact.html">Contact</a>
        <button id="login">Log In</button>
    </nav>
    <?php
    // Sample data for dentists
    $dentists = [
        'eunice_seng' => [
            'name' => 'Dr Eunice Seng',
            'bio' => 'Dr Eunice Seng is an experienced dentist specializing in xxxx. She received her dental degree from the National University of Singapore in 2000. She has been practicing dentistry for 20 years and is a member of the Singapore Dental Association.',
            'image' => 'Assets/temp.png'
        ],
        'thong_peiyu' => [
            'name' => 'Dr Thong Peiyu',
            'bio' => 'Dr Thong Peiyu is an experienced dentist specializing in xxxx. She received her dental degree from the National University of Singapore in 2000. She has been practicing dentistry for 20 years and is a member of the Singapore Dental Association.',
            'image' => 'Assets/temp.png'
        ],
        'ali_abu' => [
            'name' => 'Dr Ali Abu Bin Akau',
            'bio' => 'Dr Ali Abu Bin Akau is an experienced dentist specializing in xxxx. She received her dental degree from the National University of Singapore in 2000. She has been practicing dentistry for 20 years and is a member of the Singapore Dental Association.',
            'image' => 'Assets/temp.png'
        ]
    ];

    // Get the dentist identifier from the query parameter
    $dentist_id = $_GET['dentist'] ?? '';

    // Fetch the dentist data
    $dentist = $dentists[$dentist_id] ?? null;

    if ($dentist) {
        echo "<div>";
        echo "<!--image of the dentist on the left-->";
        echo "<img src='{$dentist['image']}' width='200px' alt='temp'><br>";
        echo "</div>";

        echo "<div>";
        echo "<h1>{$dentist['name']}</h1>";
        echo "<p>{$dentist['bio']}</p>";
        echo "</div>";
    } else {
        echo "<p>Dentist not found.</p>";
    }
    ?>
    <!--Make this a nice box-->
    <a href="book_appointment.php">Book Appointment</a>
<footer>
        <small>
            <i>Copyright &copy; 2024 Bright Smiles Dental</i> &nbsp;
            <a href="index.html">Home</a> &nbsp;
            <a href="dentists.html">Dentists</a> &nbsp;
            <a href="services.html">Services</a> &nbsp;
            <a href="book_appointment.php">Book Appointment</a> &nbsp;
            <a href="manage_booking.php">Manage Booking</a> &nbsp;
            <a href="contact.html">Contact</a>
        </small>
    </footer>
</div>
</body>
</html>