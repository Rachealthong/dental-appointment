<?php
session_start();

$old_user = $_SESSION['patient_id'];
unset($_SESSION['patient_id']);
session_destroy();

if (!empty($old_user)) {
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