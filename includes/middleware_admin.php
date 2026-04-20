<?php
require 'auth.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access Denied");
}
?>