<?php
include '../dbconnect.php';

$default_password = md5('defaultpassword');

$sql = "INSERT INTO dentists (dentist_name, dentist_email, dentist_password) 
VALUES 
('Dr Eunice Seng', 'euniceseng@example.com', '$default_password'), 
('Dr Thong Peiyu', 'thongpeiyu@example.com', '$default_password'), 
('Dr Ali Abu bin Akau', 'aliabu@example.com', '$default_password')";

if ($conn->query($sql) === TRUE) {
    echo "New dentists registered successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>