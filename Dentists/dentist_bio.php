<!DOCTYPE html>
<html lang="en">
<head>
<title>Dentist Biodata</title>
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
    // Sample data for dentists
    $dentists = [
        'eunice_seng' => [
            'name' => 'Dr Eunice Seng',
            'bio' => 'Dr Eunice Seng is an experienced dentist specializing in xxxx. She received her dental degree from the National University of Singapore in 2000. She has been practicing dentistry for 20 years and is a member of the Singapore Dental Association.',
            'image' => '../Assets/temp.png'
        ],
        'thong_peiyu' => [
            'name' => 'Dr Thong Peiyu',
            'bio' => 'Dr Thong Peiyu is an experienced dentist specializing in xxxx. She received her dental degree from the National University of Singapore in 2000. She has been practicing dentistry for 20 years and is a member of the Singapore Dental Association.',
            'image' => '../Assets/temp.png'
        ],
        'ali_abu' => [
            'name' => 'Dr Ali Abu Bin Akau',
            'bio' => 'Dr Ali Abu Bin Akau is an experienced dentist specializing in xxxx. She received her dental degree from the National University of Singapore in 2000. She has been practicing dentistry for 20 years and is a member of the Singapore Dental Association.',
            'image' => '../Assets/temp.png'
        ]
    ];

    // Get the dentist identifier from the query parameter
    $dentist_id = $_GET['dentist'] ?? '';

    // Fetch the dentist data
    $dentist = $dentists[$dentist_id] ?? null;

    if ($dentist) {
        echo "<div class='container_service'>";

        echo "<div id='service_image'>";
        echo "<!--image of the dentist on the left-->";
        echo "<img src='{$dentist['image']}' width='200px' alt='temp'><br>";
        echo "</div>";

        echo "<div id='service_detail'>";
        echo "<h1>{$dentist['name']}</h1>";
        echo "<p>{$dentist['bio']}</p>";
        echo "<br><br>";

        echo "<div id='book_appointment'>";
        echo "<a href='../Booking Appointment/book_appointment.php'>Book Appointment</a>";
        echo "</div>";

        echo "</div>";
        echo "</div>";
    } else {
        echo "<p>Dentist not found.</p>";
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