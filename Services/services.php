<!DOCTYPE html>
<html lang="en">
<head>
<title>Services Page</title>
<meta charset="utf-8">
<link rel="stylesheet" href="../styles.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
<div id="wrapper">
    <header>
    </header>
    <div id="navbar"></div>
    <div id="services">
        <img class="img_filter" src="../Assets/ourservices.jpg">
        <div class="bottom_centered"><h1>OUR SERVICES</h1></div>
    </div>
    <!-- make these 3 boxes next to each other -->
    <div class="container">
        <?php
        include '../dbconnect.php'; // Ensure this path is correct

        // Fetch services from the database
        $sql = "SELECT service_id, service_type, service_image FROM services";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="box">';
                echo '<a href="services_detail.php?service_id=' . htmlspecialchars($row['service_id']) . '">';
                echo '<img src="../Assets/' . htmlspecialchars($row['service_image']) . '" width="200px" alt="' . htmlspecialchars($row['service_type']) . '"><br>';
                echo '<h2>' . htmlspecialchars($row['service_type']) . '</h2>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No services found.</p>';
        }

        $conn->close();
        ?>
    </div>
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