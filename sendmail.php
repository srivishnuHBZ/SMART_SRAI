<?php
include('db_connect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'phpmailer/vendor/autoload.php';

$student_id = $_GET['student_id'];
$sql = mysqli_query($conn, "SELECT * FROM students where id = $student_id");
$res = mysqli_fetch_assoc($sql);
$student_name = $res['name'];
$date = date("Y/m/d");
$sql_p = mysqli_query($conn, "SELECT * FROM parent where student_id = $student_id");
$res_p = mysqli_fetch_assoc($sql_p);
$f_name = $res_p['parent_name_f'];
$m_name = $res_p['parent_name_m'];
if ($f_name != "N/A" && $m_name != "N/A") {
	$parent = $f_name . " & " . $m_name;
} elseif ($f_name == "N/A" && $m_name == "N/A") {
	$parent = "Parent";
} elseif ($f_name != "N/A" && $m_name == "N/A") {
	$parent = $f_name;
} elseif ($f_name == "N/A" && $m_name != "N/A") {
	$parent = $m_name;
}
if (isset($_GET['date'])) {
	$date = $_GET['date'];
} else {
	$date = date("Y/m/d");
}
$email = $res_p['email'];




$mail = new PHPMailer(true);
//Set mailer to use smtp
$mail->isSMTP();
//Define smtp host
$mail->Host = "smtp.gmail.com";
//Enable smtp authentication
$mail->SMTPAuth = true;
//Set smtp encryption type (ssl/tls)
$mail->SMTPSecure = "tls";
//Port to connect smtp
$mail->Port = "587";
//Set gmail username
$mail->Username = "srivishnu2k@gmail.com"; // your email
//Set gmail password
$mail->Password = "HackerBoyz@1605"; // password
//Email subject
$mail->Subject = "Student Absent Class Nortification";
//Set sender email
$mail->setFrom('srivishnu2k@gmail.com'); // Your email
//Enable HTML
$mail->isHTML(true);
//Attachment
// $mail->addAttachment('img/attachment.png');
//Email body
$mail->Body = "<h1>Dear $parent </h1></br><p>Your children name $student_name is absent on $date</p>";
//Add recipient
$mail->addAddress('' . $email . '');
// $mail->SMTPDebug = 10;   
//Finally send email
if ($mail->send()) {
	echo "Email Sent..!!";
	// wait(3);
	echo "<script>setTimeout('window.close()', 1000);</script>";
} else {
	echo "Message could not be sent.";
}
//Closing smtp connection
	// $mail->smtpClose();
