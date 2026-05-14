<html>

<head>
    <title>PTT WEB SERVICE</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset=utf-8 />
</head>

<body>
    <table border=""></table>
    <?php
    
    require('phpmailer/sendmail.php'); //20240808 add by 04404, new function send mail.
    error_reporting(~E_NOTICE);


    date_default_timezone_set("Asia/Bangkok");
    $adate = date("Y-m-d");
    $ldate = date('Y-m-d', strtotime(' - 1 days'));

    $to = 'system-admin@ts-engineering.com';
    $subject = 'Request for quotation';
    $fromname = 'TSG System Admin';
    $message = '<html> 
							<body style="font-family:Tahoma">
							<FONT Size = "3"> 
							<center>
								<font color="red" size = "4">Request for quotation</font> <br>

                                เรียนคุณ 
                                    ระบบแจ้งเตือนอัตโนมัติให้ Approve

                                    เลข REQ >>  
                                    
                                    รายการดังนี้



                                    โปรดคลิ๊กที่ลิ้งค์เพื่อเข้า Approve
                                    
                                    http://tsa-wbsrv02.ts.tsa.co.th/E-Form/Purchase/

							</center>
							</FONT>
							</body>
							</html>';
    $headers = 'From: AuctionTSGAlert@thaisummit.co.th' . "\r\n" .
        'Reply-To: no-reply@thaisummit.co.th' . "\r\n" .
        'Content-type: text/html; charset=utf-8' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // $Send = mail($to, $subject, $message, $headers);
    $Send = fncPhpMailer($to, $subject, $message);

    if ($Send) {
        echo "Email Sending";
        // echo"<script type=\"text/javascript\">alert(\"ไม่สามารถ Update ค่าน้ำมันได้ เมลถูกส่งเรียบร้อยแล้ว!\");</script>";
    } else {
        echo "Email Can Not Send";
        //echo"<script type=\"text/javascript\">alert(\"ไม่สามารถ Update ค่าน้ำมัน และส่งเมลไม่ได้!\");</script>";
    }

    sqlsrv_close($conn);
    ?>
</body>

</html>