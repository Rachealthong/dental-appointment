<?php
include '../dbconnect.php';
session_start();

// Check if the dentist is logged in
if (!isset($_SESSION['dentist_id'])) {
    die("Access denied. Please log in as a dentist.");
}

// Debugging: Check if the POST request is received
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "POST request received.<br>";

    // Check if attendance data is set
    if (isset($_POST['attendance'])) {
        echo "Attendance data received.<br>";
        $attendance_updates = $_POST['attendance'];

        // Iterate through each attendance value and update in the database
        foreach ($attendance_updates as $appointment_id => $attendance_value) {
            if ($attendance_value !== "") {
                // Prepare the SQL query to update the attendance
                $sql = "UPDATE appointments SET attendance = ? WHERE appointment_id = ?";
                $stmt = $conn->prepare($sql);
                
                // Check if the value should be set to null
                if ($attendance_value === "") {
                    $attendance_value = null;
                }
                
                $stmt->bind_param("ii", $attendance_value, $appointment_id);

                if ($stmt->execute()) {
                    echo "Attendance updated successfully for appointment ID: " . $appointment_id . "<br>";
                } else {
                    echo "Error updating attendance for appointment ID: " . $appointment_id . ": " . $stmt->error . "<br>";
                }
                $stmt->close();
            } else {
                echo "Attendance value for appointment ID " . $appointment_id . " was not set.<br>";
            }
        }
    } else {
        echo "No attendance data received.<br>";
    }
} else {
    echo "No POST request detected.<br>";
}

// Redirect back to the Manage Booking page after 3 seconds
echo "<br>Redirecting back to Manage Booking...";
header("refresh:3; url=manage_booking_dentist.php");

$conn->close();
?>
