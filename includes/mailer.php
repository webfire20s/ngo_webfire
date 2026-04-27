<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

function sendMail($to, $subject, $body) {

    $mail = new PHPMailer(true);

    try {

        /* SMTP CONFIG */
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sarthakagrwal10@gmail.com';
        $mail->Password   = 'jndvutctpnsosvii'; // NOT normal password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        /* EMAIL DETAILS */
        $mail->setFrom('sarthakagrwal10@gmail.com', 'Auto NGO');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();

        return true;

    } catch (Exception $e) {
        return false;
    }
}