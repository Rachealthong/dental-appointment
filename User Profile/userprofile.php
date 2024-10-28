<?php
session_start();
include('../dbconnect.php');

// Check if the user is logged in
$is_logged_in = isset($_SESSION['patient_id']);
$patient_id = $_SESSION['patient_id'] ?? null;

// Redirect to login if not logged in
if (!$is_logged_in) {
    header('Location: ../User Registration/login.html');
    exit();
}

// Fetch patient data
$patient_data = null;
if ($patient_id) {
    $stmt = $conn->prepare("SELECT patient_name, patient_email, patient_password, patient_phoneno, patient_gender, patient_nationality, patient_dob FROM patients WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient_data = $result->fetch_assoc();
    $stmt->close();
}

$password = md5($password);

// Update patient data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $patient_id) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone_no = $_POST['phone_no'];
    $gender = $_POST['gender'];
    $nationality = $_POST['nationality'];
    $dob = $_POST['dob'];

    $stmt = $conn->prepare("UPDATE patients SET patient_name = ?, patient_email = ?, patient_password = ?, patient_phoneno = ?, patient_gender = ?, patient_nationality = ?, patient_dob = ? WHERE patient_id = ?");
    $stmt->bind_param("sssssssi", $name, $email, $password, $phone_no, $gender, $nationality, $dob, $patient_id);
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
            name: "<?php echo htmlspecialchars($patient_data['patient_name']); ?>",
            email: "<?php echo htmlspecialchars($patient_data['patient_email']); ?>",
            password: "<?php echo htmlspecialchars($patient_data['patient_password']); ?>",
            phone_no: "<?php echo htmlspecialchars($patient_data['patient_phoneno']); ?>",
            gender: "<?php echo htmlspecialchars($patient_data['patient_gender']); ?>",
            nationality: "<?php echo htmlspecialchars($patient_data['patient_nationality']); ?>",
            dob: "<?php echo htmlspecialchars($patient_data['patient_dob']); ?>"
        };

        function handleSubmit(event) {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById("confirmpw").value;
            const phone_no = document.getElementById('phone_no').value;
            const gender = document.getElementById('gender').value;
            const nationality = document.getElementById('nationality').value;
            const dob = document.getElementById('dob').value;

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

             // Password validation
            var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
            if (!passwordPattern.test(password)) {
                alert("Password should be at least 8 characters long and contain one of the special characters !@#$%^&*, one lowercase letter, one uppercase letter, and one digit.");
                return false;
            } 

            // Confirm Password validation
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            var phonePattern = /^\d{8}$/;
            if (!phonePattern.test(phone_no)) {
                alert("Phone number should be 8 digits long.");
                return false;
            }

            var today = new Date();
            var selectedDate = new Date(dob);
            if (selectedDate >= today) {
                alert("Date of Birth cannot be today or in the future.");
                return false;
            }

            // Check if any value has changed
            if (name !== originalData.name || email !== originalData.email || password !== originalData.password || phone_no !== originalData.phone_no || 
                gender !== originalData.gender || nationality !== originalData.nationality ||
                dob !== originalData.dob) {
                alert('Profile updated!');
            } else {
                // Prevent form submission if no changes were made
                event.preventDefault();
                alert('No changes made to the profile.');
            }

            return true;
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
        <?php if ($patient_data): ?>
            <form id="userprofile_form" method="post" onsubmit="handleSubmit(event)">
                <div class="userprofile_row">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($patient_data['patient_name']); ?>" required>
                </div>

                <div class="userprofile_row">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($patient_data['patient_email']); ?>" required>
                </div>

                <div class="userprofile_row">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($patient_data['patient_password']); ?>" required>
                </div>

                <div class="userprofile_row">
                <label for="confirmpw">Confirm Password:</label>
                 <input type="password" id="confirmpw" name="confirmpw" value="<?php echo htmlspecialchars($patient_data['patient_password']); ?>"required>
                </div>

                <div class="userprofile_row">
                    <label for="phone_no">Phone Number:</label>
                    <input type="text" id="phone_no" name="phone_no" value="<?php echo htmlspecialchars($patient_data['patient_phoneno']); ?>" required>
                </div>

                <div class="userprofile_row">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender">
                        <option value="Male" <?php echo $patient_data['patient_gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $patient_data['patient_gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo $patient_data['patient_gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="userprofile_row">
                <label for="nationality">Nationality:</label>
                <select id="nationality" name="nationality" required>
                    <option value="Singaporean" <?php echo ($patient_data['patient_nationality'] === 'Singaporean') ? 'selected' : ''; ?>>Singaporean</option>
                    <option value="Singapore PR" <?php echo ($patient_data['patient_nationality'] === 'Singapore PR') ? 'selected' : ''; ?>>Singapore PR</option>
                    <option value="Foreigner" <?php echo ($patient_data['patient_nationality'] === 'Foreigner') ? 'selected' : ''; ?>>Foreigner</option>
                </select>
                </div>

                <div class="userprofile_row">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($patient_data['patient_dob']); ?>" required>
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
    fetch('../Elements/navbar.php')
        .then(response => response.text())
        .then(data => document.getElementById('navbar').innerHTML = data);
    fetch('../Elements/footer.html')
    .then(response => response.text())
    .then(data => document.getElementById('footer').innerHTML = data);
</script>
</body>
</html>
