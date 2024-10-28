<?php
session_start();
include '..\dbconnect.php';

$patient_id = $_SESSION['patient_id'];  // Assuming patient ID is stored in session
$appointment_id = $_POST['appointment_id'];
$action = $_POST['action'] ?? '';

// Cancel the appointment
    
$sql1 = "UPDATE appointments SET cancelled = 1 WHERE appointment_id = ? AND patient_id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("ii", $appointment_id, $patient_id);
$stmt1->execute();

// Update the schedule availability
$sql2 = "UPDATE schedule SET availability_status = 1 WHERE schedule_id = (SELECT schedule_id FROM appointments WHERE appointment_id = ?)";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $appointment_id);
$stmt2->execute();

header("Location: cancel_confirmation.php?appointment_id=" . $appointment_id);
exit();
