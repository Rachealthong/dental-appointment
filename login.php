<?php
include 'dbconnect.php';
session_start();

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $password = md5($password);
    $sql = "SELECT * FROM Patients WHERE patient_email = '$email' AND patient_password = '$password'";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['patient_id'] = $row['patient_id'];
        $email_parts = explode('@', $email);
        $_SESSION['email'] = $email_parts[0];
        header('Location: ../Booking Appointment/book_appointment.php');
        exit;
    } else {
        echo "<script type='text/javascript'>
        alert('Login failed.');
        window.location.href = 'login.html';
        </script>";
    }
}