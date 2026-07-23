<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (isset($_POST['vercode'])) {
  if ((empty($_SESSION["vercode"])) || ($_SESSION["vercode"] != $_POST['vercode'])) {
    die("<script>alert('Invalid Verification Code'); history.back();</script>");
  }
}


require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

require 'config.php';

try {
    $name         = $_POST['name'] ?? '';
    $email        = $_POST['email'] ?? '';
    $phone        = $_POST['phoneno'] ?? '';
    $department        = $_POST['department'] ?? '';
    $organisation        = $_POST['organisation'] ?? '';
    $city        = $_POST['city'] ?? '';
    $state        = $_POST['state'] ?? '';
    $country        = $_POST['country'] ?? '';
    $paperTitle        = $_POST['paper-title'] ?? '';
    $uploads        = $_POST['uploads'] ?? '';
    $vpage_url = $_POST['pageurl'] ?? '';
    $page_name    =$_POST['pagename']??'';

    $isValidEmail = !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);

 $stmt = $conn->prepare("INSERT INTO req_query_table (full_name, phone_number, email, department, organisationn, city, state, country, paper_title, uploads, page message, page_name,page_url) VALUES (?, ?, ?, ?, ?, ?,?)");
    $stmt->bind_param("sssssss", $name,$countrycode, $phone, $email, $message, $page_name,$vpage_url);

    if ($stmt->execute()) {
    // echo "Data savsed to database successfully<br>";
    } else {
        echo "Database Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // === 1️⃣ Send main mail to you ===
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'mail.crmwala.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'query@crmwala.com';
    $mail->Password   = 'rkt.email@121';
    $mail->SMTPSecure = 'ssl'; // try 'smtp' if this fails
    $mail->Port       = 465;   // try 465 for smtp


    // $mail->setFrom('query@crmwala.com', 'CRMWala');
    if ($isValidEmail) $mail->addReplyTo($email, $name);
    $mail->addAddress('crmwala@gmail.com', 'CRMWala');

    $mail->isHTML(true);
    $mail->Subject = "New Contact Form Submission";
    $mail->Body = "
        <h3>New message from your website:</h3>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Phone No:</strong> +{$countrycode}-{$phone}</p>
        <p><strong>Email :</strong> {$email}</p>
        <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
    ";

    $mail->send();

    // === 2️⃣ Send confirmation mail to user ===
    // === 2️⃣ Send confirmation mail to user (CORRECT WAY) ===
if ($isValidEmail) {

    $mail->clearAddresses();   // very important
    $mail->clearReplyTos();    // very important

    $mail->addAddress($email, $name);
    // $mail->setFrom('query@crmwala.com', 'CRMWALA.com');
    $mail->addReplyTo('crmwala@gmail.com', 'CRMWala');

    $mail->Subject = 'We have received your message';
    $mail->Body = "
        <p>Dear <b>{$name}</b>,</p>
        <p>Phone No:+{$countrycode}-{$phone}</p>
        <p>Email : {$email}</p>
        <p>Thank you for reaching out to <b>CRMWALA.com</b>. We’ve received your message and will get back to you soon.</p>
        <p><b>Your Message:</b><br>" . nl2br(htmlspecialchars($message)) . "</p>
        <br><p>Best regards,<br><b>CRMWALA Team</b></p>
    ";

    $mail->send();
}


    echo "<script>alert('✔️ Message has been sent successfully!'); window.location.href='../thankyou';</script>";

} catch (Exception $e) {
    echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
