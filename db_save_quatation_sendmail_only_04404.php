<?php
include("connect.php");
require('phpmailer/sendmail.php'); //20240808 add by 04404, new function send mail.
error_reporting(~E_NOTICE);
date_default_timezone_set("Asia/Bangkok");

header('Content-Type: text/html; charset=UTF-8');

$num_req = 'REQ2026-01-003'; // ******************** เลขที่ RFQ

// รายละเอียดต่างๆ ดูจาก TSECD -> db: E-From_Purchase

$name_request = 'นางสาวบุษบา ยาโพธิ์'; // ******************** ชื่อผู้เปิดเอกสาร
$email = ''; // ******************** อีเมลผู้เปิดเอกสาร
$department = 'C.I.C'; // ******************** แผนกผู้เปิดเอกสาร
$tel = ''; // ******************** เบอร์ติดต่อผู้เปิดเอกสาร

$sqlA = "SELECT * FROM quatation WHERE num_req = '$num_req' ";
sqlsrv_query($conn, "SET NAMES UTF8");
$queryA = sqlsrv_query($conn, $sqlA);
$resultA = sqlsrv_fetch_array($queryA, SQLSRV_FETCH_ASSOC);
$quatation_ID = $resultA["quatation_ID"];

date_default_timezone_set("Asia/Bangkok");
$adate = date("Y-m-d");
$ldate = date('Y-m-d', strtotime(' - 1 days'));

$to = 'kowit_d@ts-engineering.com'; // ******************** อีเมลผู้อนุมัติ
$approver_user_nameTH = 'MS.JITTRA AEMPONG'; // ******************** ชื่อผู้อนุมัติ
$comment_user = '@2026-01-05 14:46:57 => ตราปั้ม ไม่มี ปี พ.ศ 2026'; // ******************** คอมเม้นต์ผู้เปิดเอกสาร
$subject = 'Alert from System > Request for quotation';
$fromname = 'ระบบ Request for quotation';
$message = '<html> 

                <body style="font-family: Tahoma;">
                
                <FONT Size = "3"> 
                
                <H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
                <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 
                    
                    <b> Dear, ' . $approver_user_nameTH . '</b> <br> <br>
                        
                    &nbsp;&nbsp; ขณะนี้ผู้ร้องขอได้ดำเนินการสร้าง <b>เอกสารเลขที่ : ' . $num_req . '</b><br> 
                        
                    &nbsp;&nbsp; ข้อคิดเห็นของ ผู้ร้องขอ วันเวลา <b> ' . $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_user)) . '</b> <br> <br>

                        
                    
                    &nbsp;&nbsp; <table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
                    <tbody>
                    <tr >
                    <td style="border: 2px solid #ccc; border-collapse: collapse; ">
                        <table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
                                <thead>                                        
                                    <tr>
                                        <th >Status</th>
                                        <th width="15%">ผู้ร้องขอ</th>
                                        <th>อีเมล์</th>
                                        <th>แผนก</th>
                                        <th>เบอร์ติดต่อ</th>
                                        <th>วันที่ขอ</th>
                                        
                                    </tr>
                                </thead>
                                <tbody >
                                    <td>Pending (รออนุมัติ)</td>
                                    <td>' . $name_request . '</td>
                                    <td>' . $email . '</td>
                                    <td>' . $department . '</td>
                                    <td>' . $tel . '</td>
                            
                                    <td>' .  date('d/m/Y, H:i:s')  . '</td>
                                </tbody>
                            </table>
                            </td>
                        </tr>
                        </tbody>
                        </table>    
                        <br> 
                    
                        &nbsp;&nbsp; คลิกที่ลิงก์เพื่อเข้าใช้งานระบบ <br> 												
                        
                        &nbsp;&nbsp; ' . $linkProgram . '<br><br>
                        
                        NOTICE: This mail is automatic from system. Please do not reply to this note.<br>    
                
                </FONT>
                
                </body>
                </html>';
$headers = 'From: NoReply.RFQ@ts-engineering.com' . "\r\n" .
    // 'CC: no-reply@ts-engineering.com' . "\r\n" .
    // 'Reply-To: no-reply@ts-engineering.com' . "\r\n" .
    'Content-type: text/html; charset=utf-8' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$Send = fncPhpMailer($to, $subject, $message);

if ($Send) {
    echo "Email Sending";
} else {
    echo "Email Can Not Send";
}

sqlsrv_close($conn);

?>
