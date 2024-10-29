<!DOCTYPE html>
<html lang="en">
<head>
<title>Dentist Page</title>
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
    <div style="clear: both;"></div>
    <div id="dentist_content">
        <div id="dentist">
            <img class="img_filter" src="../Assets/dental_team.png">
            <div class="bottom_centered"><h1>OUR DENTISTS</h1></div>
        </div>
        <!-- make these 3 boxes next to each other -->
        <div class="container">
        <?php
        include '../dbconnect.php'; // Ensure this path is correct

        // Fetch services from the database
        $sql = "SELECT dentist_id, dentist_name, dentist_image, dentist_description FROM dentists";
        $result = $conn->query($sql);
        

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
            $description = $row['dentist_description'];

            // Extract content within the first <h3> tag
            preg_match('/<h3>(.*?)<\/h3>/', $description, $matches);
            $title = $matches[1] ?? '';

            // Remove the <h3> section from the description to focus on bio content
            $bio = preg_replace('/<h3>.*?<\/h3>/', '', $description);

            // Extract few words
            $words = explode(' ', strip_tags($bio));
            $bioPreview = implode(' ', array_slice($words, 0, 20)) . ' ...';
                echo '<div class="box-dentist">';
                echo '<a href="dentist_bio.php?dentist_id=' . htmlspecialchars($row['dentist_id']) . '">';
                echo '<img src="../Assets/' . htmlspecialchars($row['dentist_image']) . '" width="400px" alt="' . htmlspecialchars($row['dentist_name']) . '"><br>';
                echo '<h2>' . htmlspecialchars($row['dentist_name']) . '</h2>';
                echo "<h3>$title</h3>";
                echo '<div class="dentist-desc">' ;//. htmlspecialchars_decode($row['dentist_description']) . '</div>'; 
                
                echo "<p>$bioPreview</p>";
                echo '</div>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No services found.</p>';
        }

        $conn->close();
        ?>
    </div>
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