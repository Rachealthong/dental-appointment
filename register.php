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
        window.location.href = 'login.html';
    </script>";
} else {
    echo "<script type='text/javascript'>
        alert('Registration failed.');
    </script>";
}
?>