<?php
session_start();
include '../dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['patient_id']) && !isset($_SESSION['dentist_id'])) {
    die("You must be logged in to submit an appointment.");
}

// Get form data
$appointment_id = $_POST['appointment_id'];
$patient_id = $_SESSION['patient_id'] ?? null;
$dentist_name = $_POST['dentist'];
$service_type = $_POST['service'];
$preferred_date = $_POST['date'];
$preferred_time = $_POST['time'];
$remarks = $_POST['remarks'];


// Fetch patient_id
$stmt = $conn->prepare("SELECT patient_id FROM patients WHERE patient_id= ?");
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$stmt->bind_result($patient_id);
$stmt->fetch();
$stmt->close();

if (!$patient_id) {
    die("Patient not found.");
}

$stmt = $conn->prepare("SELECT patient_name FROM patients WHERE patient_id= ?");
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$stmt->bind_result($patient_name);
$stmt->fetch();
$stmt->close();

if (!$patient_id) {
    die("Patient not found.");
}

// Fetch dentist_id
$stmt = $conn->prepare("SELECT dentist_id FROM dentists WHERE dentist_name = ?");
$stmt->bind_param("s", $dentist_name);
$stmt->execute();
$stmt->bind_result($dentist_id);
$stmt->fetch();
$stmt->close();

if (!$dentist_id) {
    die("Dentist not found.");
}

// Fetch service_id
$stmt = $conn->prepare("SELECT service_id FROM services WHERE service_type = ?");
$stmt->bind_param("s", $service_type);
$stmt->execute();
$stmt->bind_result($service_id);
$stmt->fetch();
$stmt->close();

if (!$service_id) {
    die("Service not found.");
}

//Fetch original schedule_id from the existing appointment
$stmt = $conn->prepare("SELECT schedule_id FROM appointments WHERE appointment_id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$stmt->bind_result($original_schedule_id);
$stmt->fetch();
$stmt->close();

if (!$original_schedule_id) {
    die("Original schedule not found.");
}

// Fetch new schedule_id
$stmt = $conn->prepare("SELECT schedule_id FROM schedule WHERE dentist_id = ? AND available_date = ? AND available_time = ?");
$stmt->bind_param("iss", $dentist_id, $preferred_date, $preferred_time);
$stmt->execute();
$stmt->bind_result($schedule_id);
$stmt->fetch();
$stmt->close();

if (!$schedule_id) {
    die("Schedule slot not available.");
}

// Begin transaction
$conn->begin_transaction();


try {
    // Set availability of the original schedule_id to 1
    $update_original_sql = "UPDATE schedule SET availability_status = 1 WHERE schedule_id = ?";
    $update_stmt = $conn->prepare($update_original_sql);
    $update_stmt->bind_param("i", $original_schedule_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Update the original appointment to be marked as rescheduled
    $update_appointment_sql = "UPDATE appointments SET rescheduled = 1 WHERE appointment_id = ? AND patient_id = ?";
    $stmt = $conn->prepare($update_appointment_sql);
    $stmt->bind_param("ii", $appointment_id, $patient_id);
    $stmt->execute();
    $stmt->close();

    // Insert a new appointment with the new schedule_id
    $insert_appointment_sql = "INSERT INTO appointments (schedule_id, dentist_id, service_id, patient_id, remarks, cancelled, rescheduled) VALUES (?, ?, ?, ?, ?, 0, 0)";
    $insert_stmt = $conn->prepare($insert_appointment_sql);
    $insert_stmt->bind_param("iiiis", $schedule_id, $dentist_id, $service_id, $patient_id, $remarks);
    $insert_stmt->execute();
    $insert_stmt->close();

    $appointment_id = $conn->insert_id;

    // Set availability of the new schedule_id to 0
    $update_new_sql = "UPDATE schedule SET availability_status = 0 WHERE schedule_id = ?";
    $new_schedule_stmt = $conn->prepare($update_new_sql);
    $new_schedule_stmt->bind_param("i", $schedule_id);
    $new_schedule_stmt->execute();
    $new_schedule_stmt->close();

    // Commit the transaction
    $conn->commit();

    // Fetch patient email for confirmation
    $stmt = $conn->prepare("SELECT patient_email FROM patients WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $stmt->bind_result($patient_email);
    $stmt->fetch();
    $stmt->close();

    // Prepare the confirmation email
    $to = 'f32ee@localhost'; //assume patient email
    $subject = "Appointment Reschedule Confirmation";
    $message = "Dear $patient_name, \n\nYour appointment has been rescheduled successfully!\n\n" .
               "Appointment ID: $appointment_id\n" .           
               "Dentist: $dentist_name\n" .
               "Service: $service_type\n" .
               "Date: $preferred_date\n" .
               "Time: $preferred_time\n\n" .
               "Regards, \n\nBright Smiles Dental.";
    $headers = "From: f31ee@localhost\r\n" . // Change to your sender email
               "Reply-To: f31ee@localhost\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // Send the email
    mail($to, $subject, $message, $headers);

    $to = 'f31ee@localhost'; //assume dentist email
    $subject = "Appointment Reschedule Confirmation";
    $message = "Dear $dentist_name, \n\nYour appointment has been rescheduled by $patient_name. Please check the new appointment details below.\n\n" .
               "Appointment ID: $appointment_id\n" .               
               "Patient: $patient_name\n".
               "Dentist: $dentist_name\n" .
               "Service: $service_type\n" .
               "Date: $preferred_date\n" .
               "Time: $preferred_time\n\n" .
               "Regards, \n\nBright Smiles Dental.";
    $headers = "From: f31ee@localhost\r\n" . // Change to your sender email
               "Reply-To: f31ee@localhost\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // Send the email
    mail($to, $subject, $message, $headers);

    // Redirect to confirmation page
    header("Location: reschedule_confirmation.php?dentist=" . urlencode($dentist_name) . 
           "&service=" . urlencode($service_type) . 
           "&date=" . urlencode($preferred_date) . 
           "&time=" . urlencode($preferred_time) .
           "&appointment_id=" . urlencode($appointment_id));
    exit();
} catch (Exception $e) {
    // Rollback the transaction on error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$stmt->close();
$conn->close();
?>
