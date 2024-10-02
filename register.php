<html>
<head>
    <title>Registration Page</title>
    <script src="formValidation.js"></script>
</head>
<body>
    <h2>Create an Account</h2>
    <form id="registrationForm" method="post" action="register.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirmpw">Confirm Password:</label>
        <input type="password" id="confirmpw" name="confirmpw" required>
        <br>
        <label for="phoneno">Phone No:</label>
        <input type="text" id="phoneno" name="phoneno" required>
        <br>
        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
        </select>
        <br>
        <label for="nationality">Nationality:</label>
        <select id="nationality" name="nationality" required>
                    <option value="Singaporean">Singaporean</option>
                    <option value="Singapore PR">Singapore PR</option>
                    <option value="Foreigner">Foreigner</option>
        </select>
        <br>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>
        <br>
        <button type="submit">Register</button>
    </form>
    <script type = "text/javascript">
        document.getElementById("registrationForm").onsubmit = validateForm;
    </script>
</html>

<?php
include 'dbconnect.php';
if (isset($_POST['submit'])) {
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) 
    || empty($_POST['confirmpw']) || empty($_POST['phoneno']) || empty($_POST['gender'])
    || empty($_POST['nationality']) || empty($_POST['dob'])) {
        echo "Please fill in all fields.";
        exit;
    }
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmpw = $_POST['confirmpw'];
$phoneno = $_POST['phoneno'];
$gender = $_POST['gender'];
$nationality = $_POST['nationality'];
$dob = $_POST['dob'];

if ($password != $confirmpw) {
    echo "Passwords do not match.";
    exit;
}

$password = md5($password);

$sql = "INSERT INTO Patients (patient_name, patient_email, patient_password, 
patient_phoneno, patient_gender, patient_nationality, patient_dob) 
VALUES ('$name', '$email', '$password', '$phoneno', '$gender', '$nationality', '$dob')";

$result = $conn->query($sql);

if ($result) {
    echo "<script type='text/javascript'>
        alert('Welcome, " . $name . "! You are now registered.');
        window.location.href = 'login.php';
    </script>";
} else {
    echo "<script type='text/javascript'>
        alert('Registration failed.');
    </script>";
}
?>