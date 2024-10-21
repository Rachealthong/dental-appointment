<?php
session_start();

if (!empty($_SESSION['patient_id']) || !empty($_SESSION['dentist_id'])) {
    unset($_SESSION['patient_id'], $_SESSION['dentist_id']);
    session_destroy();

    echo "<script type='text/javascript'>
    alert('You have been logged out.');
    window.location.href = '../Others/index.html';
    </script>";
} else {
    echo "<script type='text/javascript'>
    alert('You were not logged in, and so have not been logged out.');
    window.location.href = '../Others/index.html';
    </script>";
}
?>