<?php

function fncPhpMailer($SendTo, $Subject, $Message){
	require_once('class.phpmailer.php');

	$mail = new PHPMailer();
	$mail->IsHTML(true);
	$mail->IsSMTP();
	$mail->CharSet = "utf-8";
	$mail->SMTPAuth = true; // enable SMTP authentication
	$mail->SMTPSecure = ""; // sets the prefix to the servier
	$mail->Host = "mail.ts-engineering.com"; // sets GMAIL as the SMTP server
	$mail->Port = 25; // set the SMTP port for the GMAIL server
	$mail->Username = "alert@ts-engineering.com"; // GMAIL username
	$mail->Password = "qwaszx@1"; // GMAIL password
	$mail->From = "alert@ts-engineering.com"; // "name@yourdomain.com";
	//$mail->AddReplyTo = "support@thaicreate.com"; // Reply
	$mail->FromName = "NoReply-Quotation Request";  // set from Name
	$mail->Subject = $Subject; 
	$mail->Body = $Message;

	$arrSendTo = array_filter(explode(',', str_replace(" ", "", $SendTo)));
	foreach ($arrSendTo as $item){
		$mail->AddAddress($item); // to Address
	}
	// $mail->AddAddress($SendTo); // to Address
	// $mail->AddAddress("system-admin@ts-engineering.com", "Jakrapong"); // to Address
	// $mail->AddAddress(''); // to Address

	if(!$mail->Send()) {
		// echo "Mailer Error: " . $mail->ErrorInfo;
		return false;
	} 
	else {
		// echo "Message sent!";
		return true;
	}
}


// 20240808, For test send mail.

// $SendTo = " kowit_d@ts-engineering.com , kriangsak_k@ts-engineering.com,system-admin@ts-engineering.com,  ";
// $arrSendTo = array_filter(explode(',', str_replace(" ", "", $SendTo)));

// foreach ($arrSendTo as $item){
// 	echo "$item|-|";
// }

// $res = fncPhpMailer($SendTo, 'Subject Test', 'Body Test Message.');

// if($res){
// 	echo "Email Sending";
// }else{
// 	echo "Email Can Not Send";
// }

// 20240808, For test send mail, End.

?>
