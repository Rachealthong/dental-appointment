<?php
include '../dbconnect.php';

// Get the dentist and service from the query parameters
$dentist = $_GET['dentist'] ?? null;
$service = $_GET['service'] ?? null;

if (!$dentist || !$service) {
    http_response_code(400);
    echo 'error:Dentist and service are required';
    exit;
}

// Fetch the dentist ID from the database
$stmt = $conn->prepare("SELECT dentist_id FROM dentists WHERE dentist_name = ?");
$stmt->bind_param("s", $dentist);
$stmt->execute();
$stmt->bind_result($dentist_id);
$stmt->fetch();
$stmt->close();

if (!$dentist_id) {
    http_response_code(404);
    echo 'error:Dentist not found';
    exit;
}

// Fetch the service ID from the database
$stmt = $conn->prepare("SELECT service_id FROM services WHERE service_type= ?");
$stmt->bind_param("s", $service);
$stmt->execute();
$stmt->bind_result($service_id);
$stmt->fetch();
$stmt->close();

if (!$service_id) {
    http_response_code(404);
    echo 'error:Service not found';
    exit;
}

// Fetch available slots (dates and times) for the dentist
$sql = "
    SELECT available_date, TIME_FORMAT(available_time, '%H:%i') AS available_time
    FROM schedule
    WHERE dentist_id = ? 
    AND availability_status = TRUE 
    AND available_date >= CURDATE()+INTERVAL 1 DAY
    ORDER BY available_date, available_time
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dentist_id);
$stmt->execute();
$result = $stmt->get_result();

$available_slots = [];

while ($row = $result->fetch_assoc()) {
    $available_slots[] = $row['available_date'] . ',' . $row['available_time'];
}

$stmt->close();

header('Content-Type: text/plain');
echo implode(';', $available_slots);
?>
