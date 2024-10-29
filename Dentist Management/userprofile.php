<?php
session_start();
include('../dbconnect.php');

// Check if the user is logged in
$is_logged_in = isset($_SESSION['dentist_id']);
$dentist_id = $_SESSION['dentist_id'] ?? null;

// Redirect to login if not logged in
if (!$is_logged_in) {
    header('Location: ../User Registration/login.html');
    exit();
}

$dentist_data = null;
if ($dentist_id) {
    $stmt = $conn->prepare("SELECT dentist_name, dentist_email, dentist_description FROM dentists WHERE dentist_id = ?");
    $stmt->bind_param("i", $dentist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dentist_data = $result->fetch_assoc();
    $stmt->close();
}

// Update dentist data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $dentist_id) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // This is the new password field (if provided)

    // Check if a new password is provided
    if (!empty($password)) {
        $hashed_password = md5($password);

        // If a new password is provided, update it along with other fields
        $stmt = $conn->prepare("UPDATE dentists SET dentist_name = ?, dentist_email = ?, dentist_password = ? WHERE dentist_id = ?");
        $stmt->bind_param("ssssi", $name, $email, $hashed_password, $dentist_id);
    } else {
        // If no new password is provided, update other fields except password
        $stmt = $conn->prepare("UPDATE dentists SET dentist_name = ?, dentist_email = ? WHERE dentist_id = ?");
        $stmt->bind_param("sssi", $name, $email, $dentist_id);
    }

    $stmt->execute();
    $stmt->close();

    // Refresh to see updated details
    header("Location: userprofile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Profile</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <script>
        let originalData = {
        name: "<?php echo htmlspecialchars($dentist_data['dentist_name']); ?>",
        email: "<?php echo htmlspecialchars($dentist_data['dentist_email']); ?>",
        passwordChanged: false // Track if a new password is provided
    };

        function handleSubmit(event) {

            event.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmpw').value;

            var namePattern = /^[A-Za-z\s]+$/;
            if (!namePattern.test(name)) {
                alert("Name must contain only alphabet characters and spaces.");
                return false;
            }

            var emailPattern =  /^[\w.-]+@([\w-]+\.)+[A-Za-z]{2,}$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

             
            // Validate Password if it was provided (only if user attempts to change it)
            var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
            if (password !== "" || confirmPassword !== "") {
                if (!passwordPattern.test(password)) {
                    alert("Password should be at least 8 characters long and contain one special character !@#$%^&*, one lowercase letter, one uppercase letter, and one digit.");
                    return false;
                }

                // Validate Confirm Password
                if (password !== confirmPassword) {
                    alert("Passwords do not match.");
                    return false;
                }
                originalData.passwordChanged = true; // Mark as changed if valid
            }

            // Check if any value has changed
            if (
            name !== originalData.name || 
            email !== originalData.email || 
            description !== originalData.description || 
            originalData.passwordChanged // Only flag for change if the password was updated
            ) {
                alert('Profile updated!');
            } else {
                // Prevent form submission if no changes were made
                event.preventDefault();
                alert('No changes made to the profile.');
            }

            document.getElementById('userprofile_form').submit();
        }
    </script>
</head>
<body>
<div id="wrapper">
    <header>
        <div id="navbar"></div>
    </header>
    <div style="clear: both;"></div>
    <div id="userprofile">
        <h2>My Profile</h2>
        <?php if ($dentist_data): ?>
            <form id="userprofile_form" method="post" onsubmit="handleSubmit(event)">
                <div class="userprofile_row">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($dentist_data['dentist_name']); ?>" required>
                </div>

                <div class="userprofile_row">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($dentist_data['dentist_email']); ?>" required>
                </div>

                <div class="userprofile_row">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="" placeholder="Change password" >
                </div>

                <div class="userprofile_row">
                <label for="confirmpw">Confirm Password:</label>
                 <input type="password" id="confirmpw" name="confirmpw" value="" placeholder="Confirm new password">
                </div>

                <div class="button_container">
                    <input type="submit" value="Update Profile">
                    <button type="reset">Reset</button>
                </div>
            </form>
        <?php else: ?>
            <p>No profile data found.</p>
        <?php endif; ?>
    </div>
    <div id="footer"></div>
</div>
<script>
    fetch('navbar_dentist.php')
        .then(response => response.text())
        .then(data => document.getElementById('navbar').innerHTML = data);
    fetch('footer_dentist.html')
    .then(response => response.text())
    .then(data => document.getElementById('footer').innerHTML = data);
</script>
</body>
</html>
