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

// require 'config.php';

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
    // $uploads        = $_POST['uploads'] ?? '';
    $vpage_url = $_POST['pageurl'] ?? '';
    $page_name    =$_POST['pagename']??'';

   
    // File details
    
    if(isset($_FILES['uploads'])){
        
        $fileTmpPath = $_FILES['uploads']['tmp_name'];
        $fileName = $_FILES['uploads']['name'];
        $fileError = $_FILES['uploads']['error'];

            $isValidEmail = !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
            if ($fileError === 0) {
                $uploadDir = "uploads/";

                // Create folder if not exists
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $safeFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9\.\-\_]/", "", $fileName);
                $destination = $uploadDir . $safeFileName;
                move_uploaded_file($fileTmpPath, $destination);

                //  $stmt = $conn->prepare("INSERT INTO req_query_table (full_name, phone_number, email, department, organisation, city, state, country, paper_title, uploads, page_url, page_name) VALUES (?, ?, ?, ?, ?, ?,?,?,?,?,?,?)");
                // $stmt->bind_param("ssssssssssss", $name,$phone, $email, $department, $organisation, $city, $state, $country, $paperTitle, $destination, $vpage_url, $page_name);

                // if ($stmt->execute()) {
                // // echo "Data savsed to database successfully<br>";
                // } else {
                //     echo "Database Error: " . $stmt->error;
                // }

                // $stmt->close();
                // $conn->close();
                

                // === 1️⃣ Send main mail to you ===
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host       = 'mail.radiantjournals.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'emails@radiantjournals.com';
                    $mail->Password   = 'rj.India@121';
                    $mail->SMTPSecure = 'ssl'; // try 'smtp' if this fails
                    $mail->Port       = 465;   // try 465 for smtp


                    $mail->setFrom('emails@radiantjournals.com', 'Radiant Journals');
                    if ($isValidEmail) $mail->addReplyTo($email, $name);
                    $mail->addAddress('emails@radiantjournals.com', 'Radiant Journals');
                    $mail->addAttachment($destination, $fileName);
                    $mail->isHTML(true);
                    $mail->Subject = "New Article Submission";
                    $mail->Body = "
                        <h3>New message from your website:</h3>
                        <p><strong>Name:</strong> {$name}</p>
                        <p><strong>Phone No:</strong> {$phone}</p>
                        <p><strong>Email :</strong> {$email}</p>
                        <p><strong>Department :</strong> {$department}</p>
                        <p><strong>Organisation :</strong> {$organisation}</p>
                        <p><strong>City :</strong> {$city}</p>
                        <p><strong>State :</strong> {$state}</p>
                        <p><strong>Country :</strong> {$country}</p>
                        <p><strong>Paper Title :</strong> {$paperTitle}</p>
                        <p><strong>Uploaded Attachment :</strong> {$destination}</p>
                        ";

                if (!$mail->send()) {
                    die("Mailer Error: " . $mail->ErrorInfo);
                }


                // === 2️⃣ Send confirmation mail to user ===
                // === 2️⃣ Send confirmation mail to user (CORRECT WAY) ===
                if ($isValidEmail) {

                    $mail->clearAddresses();   // very important
                    $mail->clearReplyTos();    // very important

                    $mail->addAddress($email, $name);
                    $mail->setFrom('emails@radiantjournals.com', 'Radiant Journals.com');
                    $mail->addReplyTo('emails@radiantjournals.com', 'Radiant Journals');
                    $mail->addAttachment($destination, $fileName);
                    $mail->Subject = 'We have received your message';
                    $mail->Body = "
                        <p>Dear <b>{$name}</b>,</p>
                        <p><strong>Phone No:</strong> {$phone}</p>
                        <p><strong>Email :</strong> {$email}</p>
                        <p><strong>Department :</strong> {$department}</p>
                        <p><strong>Organisation :</strong> {$organisation}</p>
                        <p><strong>City :</strong> {$city}</p>
                        <p><strong>State :</strong> {$state}</p>
                        <p><strong>Country :</strong> {$country}</p>
                        <p><strong>Paper Title :</strong> {$paperTitle}</p>
                        <p><strong>Uploaded Attachment :</strong> {$destination}</p>
                        <p>These are the given information</p>
                        <p>Thank you for reaching out to <b>radiantjournals.com</b>. We've received your message and will get back to you soon.</p>
                        
                        <br><p>Best regards,<br><b>Radiant Journals Team</b></p>
                    ";

                    if (!$mail->send()){
                        die("Mailer Error: " . $mail->ErrorInfo);
                    }
                }
                }
                }
                echo "<script>alert('✔️ Message has been sent successfully!'); window.location.href='../thankyou.html';</script>";
}
 catch (Exception $e) {
    echo "<pre>";
    echo $mail->ErrorInfo;
    echo "\n";
    echo $e->getMessage();
    echo "</pre>";
}
?>
