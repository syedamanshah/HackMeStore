<?php
require 'libs/PHPMailer/class.phpmailer.php';
require 'libs/PHPMailer/class.smtp.php';


$mail = new PHPMailer();

$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Host = 'smtp.example.com';
$mail->Port = 587;
$mail->IsHTML(true);
$mail->Username = 'sohailbasuu@gmail.com';
$mail->Password = 'hackmestoresas';
$mail->SetFrom('no-reply@hackmestore.com');
$mail->Subject = 'Registration Successful';
$mail->SetFrom('your_email@example.com', 'HackMeStore');
$mail->Body = 'Thank You for Registering at HackMeStore.';
$mail->AddAddress('shahaman121@gmail.com');

if(!$mail->Send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}
?>