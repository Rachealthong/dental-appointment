<?php
include '../dbconnect.php';

// Validate required POST data
$dentist_id = $_POST['dentist_id'] ?? null;
$dates = $_POST['date'] ?? [];
$times = $_POST['time'] ?? [];

if (!$dentist_id || empty($dates) || empty($times) || count($dates) !== count($times)) {
    http_response_code(400);
    echo 'Invalid input.';
    exit;
}

// Prepare the update query
$sql = "
    UPDATE schedule
    SET availability_status = 0
    WHERE dentist_id = ? AND available_date = ? AND available_time = ?
";
$stmt = $conn->prepare($sql);

// Loop through the dates and times to update each slot
foreach ($dates as $index => $date) {
    $time = $times[$index];

    $stmt->bind_param("iss", $dentist_id, $date, $time);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo "Error updating slot on $date at $time.";
        exit;
    }
}

$stmt->close();
$conn->close();
echo 'Slots updated successfully.';
?>
