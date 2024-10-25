<?php
include '../dbconnect.php';

$dentist_id = $_GET['dentist_id'] ?? null;

if (!$dentist_id) { 
    http_response_code(400);
    echo 'error=Dentist selection is required';
    exit;
}

// Fetch available slots for the dentist
$sql = "
    SELECT available_date, TIME_FORMAT(available_time, '%H:%i') AS available_time, availability_status
    FROM schedule
    WHERE dentist_id = ?
    ORDER BY available_date, available_time
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dentist_id);
$stmt->execute();
$result = $stmt->get_result();

$query_string = '';

while ($row = $result->fetch_assoc()) {
    $available_date = urlencode($row['available_date']);
    $available_time = urlencode($row['available_time']);
    $availability_status = (int)$row['availability_status'];
    
    $query_string .= "date[]=$available_date&time[]=$available_time&status[]=$availability_status&";
}

$stmt->close();
$conn->close();

// Remove the trailing '&' and output the query string
echo rtrim($query_string, '&');
?>
