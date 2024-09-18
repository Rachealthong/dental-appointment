<!DOCTYPE html>
<html lang="en">
<head>
<title>Service Detail Page</title>
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
    $services = [
        'service1' => [
            'name' => 'Service 1',
            'description' => 'Service 1 is a dental service that provides xxxx. Our team of experienced dentists will ensure that you receive the best care possible. We use the latest technology and techniques to provide you with the best possible results.',
            'image' => 'Assets/temp.png'
        ],
        'service2' => [
            'name' => 'Service 2',
            'description' => 'Service 2 is a dental service that provides xxxx. Our team of experienced dentists will ensure that you receive the best care possible. We use the latest technology and techniques to provide you with the best possible results.',
            'image' => 'Assets/temp.png'
        ],
        'service3' => [
            'name' => 'Service 3',
            'description' => 'Service 3 is a dental service that provides xxxx. Our team of experienced dentists will ensure that you receive the best care possible. We use the latest technology and techniques to provide you with the best possible results.',
            'image' => 'Assets/temp.png'
        ]
    ];

    $service_id = $_GET['service'] ?? '';

    $service = $services[$service_id] ?? null;

    if ($service) {
        echo "<div>";
        echo "<!--image of the service on top-->";
        echo "<img src='{$service['image']}' width='200px' alt='temp'><br>";
        echo "</div>";

        echo "<div>";
        echo "<h1>{$service['name']}</h1>";
        echo "<p>{$service['description']}</p>";
        echo "</div>";
    } else {
        echo "<p>Service not found.</p>";
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