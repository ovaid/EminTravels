<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer-master/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/PHPMailer-master/src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname   = htmlspecialchars($_POST['fname'] ?? '');
    $lname   = htmlspecialchars($_POST['lname'] ?? '');
    $email   = htmlspecialchars($_POST['email'] ?? '');
    $phone   = htmlspecialchars($_POST['phone'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Basic validation (server-side too)
    if (empty($fname) || empty($lname) || empty($email) || empty($phone) || empty($message)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required.']);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP config
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'emintravels@gmail.com'; // Your Gmail
        $mail->Password   = 'cdxyqpybxsfynxcv';       // App-specific password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender and recipient
        $mail->setFrom($email, "$fname $lname");
        $mail->addAddress('emintravels@gmail.com'); // Receiver

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body    = "
            <strong>First Name:</strong> $fname<br>
            <strong>Last Name:</strong> $lname<br>
            <strong>Email:</strong> $email<br>
            <strong>Phone:</strong> $phone<br>
            <strong>Message:</strong><br>$message
        ";

        // Send the email
        $mail->send();

        // Success JSON
        echo json_encode(['success' => true]);
        exit;

    } catch (Exception $e) {
        // Fail JSON
        echo json_encode([
            'success' => false,
            'error' => "Mailer Error: {$mail->ErrorInfo}"
        ]);
        exit;
    }
} else {
    // Invalid access method
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit;
}
