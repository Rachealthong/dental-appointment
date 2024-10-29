<?php
session_start();
include('../dbconnect.php');

// Check if the user is logged in
$is_logged_in = isset($_SESSION['dentist_id']);
$dentist_id = $_SESSION['dentist_id'] ?? null;

// Redirect to login if not logged in
if (!$is_logged_in) {
    header('Location: ../User Registration/login.html');
    exit();
}

// Update dentist data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $dentist_id) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // New password field (if provided)
    $description = $_POST['description'];

    // Check if a new password is provided
    if (!empty($password)) {
        $hashed_password = md5($password);

        // If a new password is provided, update it along with other fields
        $stmt = $conn->prepare("UPDATE dentists SET dentist_name = ?, dentist_email = ?, dentist_password = ?, dentist_description = ? WHERE dentist_id = ?");
        $stmt->bind_param("ssssi", $name, $email, $hashed_password, $description, $dentist_id);
    } else {
        // If no new password is provided, update other fields except password
        $stmt = $conn->prepare("UPDATE dentists SET dentist_name = ?, dentist_email = ?, dentist_description = ? WHERE dentist_id = ?");
        $stmt->bind_param("sssi", $name, $email, $description, $dentist_id);
    }

    $stmt->execute();
    $stmt->close();

    // Redirect back to profile page
    header("Location: userprofile.php");
    exit();
}
?>
