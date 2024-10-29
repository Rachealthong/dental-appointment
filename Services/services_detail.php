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
        include '../dbconnect.php';

        $service_id = $_GET['service_id'] ?? '';

        // Fetch main service from the database
        $sql = "SELECT service_type, service_description, service_image FROM services WHERE service_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $service = $result->fetch_assoc();
        $stmt->close();

        if ($service) {
            echo "<div class='container_service'>";

            echo "<div id='service_image'>";
            echo "<!--image of the service on top-->";
            echo "<img src='../Assets/{$service['service_image']}' width='400px' alt='temp'><br>";
            echo "</div>";

            echo "<div id='service_detail'>";
            echo "<h1>{$service['service_type']}</h1>";
            echo "<p>{$service['service_description']}</p>";
            echo "<br><br>";
            echo "<div id='book_appointment'>";
            echo "<a href='../Booking Appointment/book_appointment.php'>Book Appointment</a>";
            echo "</div>";
            echo "</div>";

            echo "</div>";

            // Fetch other services
            $sql = "SELECT service_id, service_type, service_image, service_description FROM services WHERE service_id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $service_id);
            $stmt->execute();
            $other_services = $stmt->get_result();

            echo "<div class='container_details'>";

            echo "<div id='details_header'><h2>Other Services</h2>";

            if ($other_services->num_rows > 0) {
                echo "<div class='container'>";

                while ($other_service = $other_services->fetch_assoc()) {
                    echo "<div class='box'>";
                    echo "<a href='?service_id={$other_service['service_id']}'>";
                    echo "<img src='../Assets/{$other_service['service_image']}' width='400px' alt='service'>";
                    echo "<h2>{$other_service['service_type']}</h2>";
                    $descriptionWords = explode(' ', strip_tags($other_service['service_description']));
                    $shortDescription = implode(' ', array_slice($descriptionWords, 0, 18)) . ' ...';
    
                    echo '<p class="service-description">' . htmlspecialchars($shortDescription) . '</p>';
                    //echo '<p class="service-description">' . htmlspecialchars(explode('.', $other_service['service_description'])[0] . '.') . '</p>';
                    echo "</a>";
                    echo "</div>";
                }

                echo "</div>"; // Closing div for .other_services
            }

            echo "</div>"; // Closing div for <div><h2>Other Services</h2>...</div>
            echo "</div>"; // Closing div for .container

            $stmt->close();

        } else {
            echo "<p>Service not found.</p>";
        }

        $conn->close();
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