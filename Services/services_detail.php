<!DOCTYPE html>
<html lang="en">
<head>
<title>Service Detail Page</title>
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
    <?php
    $services = [
        'service1' => [
            'name' => 'Service 1',
            'description' => 'Service 1 is a dental service that provides xxxx. Our team of experienced dentists will ensure that you receive the best care possible. We use the latest technology and techniques to provide you with the best possible results.',
            'image' => '../Assets/temp.png'
        ],
        'service2' => [
            'name' => 'Service 2',
            'description../Assets/temp.png'
        ],
        'service3' => [
            'name' => 'Service 3',
            'description' => 'Service 3 is a dental service that provides xxxx. Our team of experienced dentists will ensure that you receive the best care possible. We use the latest technology and techniques to provide you with the best possible results.',
            'image' => '../Assets/temp.png'
        ]
    ];

    $service_id = $_GET['service'] ?? '';

    $service = $services[$service_id] ?? null;

    if ($service) {
        echo "<div class='container_service'>";

        echo "<div id='service_image'>";
        echo "<!--image of the service on top-->";
        echo "<img src='{$service['image']}' width='200px' alt='temp'><br>";
        echo "</div>";

        echo "<div id='service_detail'>";
        echo "<h1>{$service['name']}</h1>";
        echo "<p>{$service['description']}</p>";
        echo "<br><br>";
        echo "<div id='book_appointment'>";
        echo "<a href='../Booking Appointment/book_appointment.php'>Book Appointment</a>";
        echo "</div>";
        echo "</div>";

        echo "</div>";
    } else {
        echo "<p>Service not found.</p>";
    }
    ?>

    <div id="footer"></div>
</div>
<script>
    fetch('../Elements/navbar.php')
        .then(response => response.text())
        .then(data => document.getElementById('navbar').innerHTML = data);
    fetch('../Elements/footer.html')
        .then(response => response.text())
        .then(data => document.getElementById('footer').innerHTML = data);
</script>
</body>
</html>