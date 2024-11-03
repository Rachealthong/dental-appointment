<?php
session_start();
include '../dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['patient_id'])) {
    die("You must be logged in to submit an appointment.");
}

$patient_id = $_SESSION['patient_id']; // Get the logged-in patient's ID

// Get form data
$dentist_name = $_POST['dentist'];
$service_type = $_POST['service'];
$preferred_date = $_POST['date'];
$preferred_time = $_POST['time'];
$remarks = $_POST['remarks'];

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

// Fetch schedule_id
$stmt = $conn->prepare("SELECT schedule_id FROM schedule WHERE dentist_id = ? AND available_date = ? AND available_time = ?");
$stmt->bind_param("iss", $dentist_id, $preferred_date, $preferred_time);
$stmt->execute();
$stmt->bind_result($schedule_id);
$stmt->fetch();
$stmt->close();

if (!$schedule_id) {
    die("Schedule slot not available.");
}

// Insert the appointment into 'appointments' table
$sql = "INSERT INTO appointments (date_created, patient_id, dentist_id, schedule_id, service_id, remarks, cancelled, rescheduled) 
        VALUES (NOW(), ?, ?, ?, ?, ?, false, false)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiis", $patient_id, $dentist_id, $schedule_id, $service_id, $remarks);

if ($stmt->execute()) {
    // Update the availability_status in 'schedule' table
    $update_sql = "UPDATE schedule SET availability_status = false WHERE schedule_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $schedule_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Fetch patient email
    $stmt = $conn->prepare("SELECT patient_email FROM patients WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $stmt->bind_result($patient_email);
    $stmt->fetch();
    $stmt->close();

    // Prepare the confirmation email
    
    $to = 'f32ee@localhost'; // Change to patient_email
    $subject = "Appointment Confirmation";
    $message = "Dear $patient_name, \n\nYour appointment has been booked successfully!\n\n" .
            "Dentist: $dentist_name\n" .
            "Service: $service_type\n" .
            "Date: $preferred_date\n" .
            "Time: $preferred_time\n\n" .
            "We are looking forward to your visit.".
            "Regards, \n\nBright Smiles Dental.";
    $headers = "From: f31ee@localhost\r\n" . // Change to your sender email
            "Reply-To: f31ee@localhost\r\n" .
            "X-Mailer: PHP/" . phpversion();

    // Send the email
    mail($to, $subject, $message, $headers);

  // Redirect to confirmation page
  header("Location: confirmation.php?dentist=" . urlencode($dentist_name) . 
  "&service=" . urlencode($service_type) . 
  "&date=" . urlencode($preferred_date) . 
  "&time=" . urlencode($preferred_time));
    exit();
    } else {
    echo "Error: " . $stmt->error;
    }

$stmt->close();
$conn->close();
?>
