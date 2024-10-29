<?php
include '../dbconnect.php';
session_start();

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = md5($password);

    $sql = "SELECT * FROM Patients WHERE patient_email = '$email' AND patient_password = '$hashed_password'";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['patient_id'] = $row['patient_id'];
        $email_parts = explode('@', $email);
        $_SESSION['email'] = $email_parts[0];
        header('Location: ../Booking Appointment/book_appointment.php');
        exit;
    } 

    // Check in Dentists table
    $sql_dentist = "SELECT * FROM Dentists WHERE dentist_email = '$email' AND dentist_password = '$password'"; 
    $result_dentist = $conn->query($sql_dentist);
    
    if ($result_dentist->num_rows > 0) {
        $row = $result_dentist->fetch_assoc();
        $_SESSION['dentist_id'] = $row['dentist_id'];
        $email_parts = explode('@', $email);
        $_SESSION['email'] = $email_parts[0];
        header('Location: ../Dentist Management/manage_booking_dentist.php'); 
        exit;
    }

    echo "<script type='text/javascript'>
    alert('Login failed.');
    window.location.href = 'login.html';
    </script>";
}