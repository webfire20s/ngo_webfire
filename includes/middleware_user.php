<?php
require 'auth.php';

if ($_SESSION['role'] !== 'member') {
    die("Access Denied");
}
?>