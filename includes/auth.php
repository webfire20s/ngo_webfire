<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /AUTONGO/admin/login.php");
    exit;
}

/* अतिरिक्त सुरक्षा */
if (!isset($_SESSION['regenerated'])) {
    session_regenerate_id(true);
    $_SESSION['regenerated'] = true;
}
?>