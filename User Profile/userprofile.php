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
    $stmt = $conn->prepare("SELECT patient_name, patient_phoneno, patient_gender, patient_nationality, patient_dob FROM patients WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient_data = $result->fetch_assoc();
    $stmt->close();
}

// Update patient data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $patient_id) {
    $name = $_POST['name'];
    $phone_no = $_POST['phone_no'];
    $gender = $_POST['gender'];
    $nationality = $_POST['nationality'];
    $dob = $_POST['dob'];

    $stmt = $conn->prepare("UPDATE patients SET patient_name = ?, patient_phoneno = ?, patient_gender = ?, patient_nationality = ?, patient_dob = ? WHERE patient_id = ?");
    $stmt->bind_param("sssssi", $name, $phone_no, $gender, $nationality, $dob, $patient_id);
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
            phone_no: "<?php echo htmlspecialchars($patient_data['patient_phoneno']); ?>",
            gender: "<?php echo htmlspecialchars($patient_data['patient_gender']); ?>",
            nationality: "<?php echo htmlspecialchars($patient_data['patient_nationality']); ?>",
            dob: "<?php echo htmlspecialchars($patient_data['patient_dob']); ?>"
        };

        function handleSubmit(event) {
            const name = document.getElementById('name').value;
            const phone_no = document.getElementById('phone_no').value;
            const gender = document.getElementById('gender').value;
            const nationality = document.getElementById('nationality').value;
            const dob = document.getElementById('dob').value;

            // Check if any value has changed
            if (name !== originalData.name || phone_no !== originalData.phone_no ||
                gender !== originalData.gender || nationality !== originalData.nationality ||
                dob !== originalData.dob) {
                alert('Profile updated!');
            } else {
                // Prevent form submission if no changes were made
                event.preventDefault();
                alert('No changes made to the profile.');
            }
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
                    <input type="text" id="nationality" name="nationality" value="<?php echo htmlspecialchars($patient_data['patient_nationality']); ?>" required>
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
