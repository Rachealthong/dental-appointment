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



// Fetch appointment details for email
$sql = "SELECT a.appointment_id, d.dentist_name, s.service_type, 
               sch.available_date, sch.available_time, a.remarks, p.patient_email 
        FROM appointments a
        JOIN schedule sch ON a.schedule_id = sch.schedule_id
        JOIN dentists d ON sch.dentist_id = d.dentist_id
        JOIN services s ON a.service_id = s.service_id
        JOIN patients p ON a.patient_id = p.patient_id
        WHERE a.appointment_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

$to = 'f32ee@localhost'; // Change to patient_email
$subject = "Appointment Cancellation Confirmation";
$message = "Dear Patient,\n\nYour appointment with Dr. " . $appointment['dentist_name'] . " for " . $appointment['service_type'] . " on " . $appointment['available_date'] . " at " . $appointment['available_time'] . " has been successfully cancelled.\n\nRegards,\nDental Clinic";
$headers = "From: f31ee@localhost\r\n" . // Change to your sender email
        "Reply-To: f31ee@localhost\r\n" .
        "X-Mailer: PHP/" . phpversion();

// Send the email
mail($to, $subject, $message, $headers);

header("Location: cancel_confirmation.php?appointment_id=" . $appointment_id);
exit();
