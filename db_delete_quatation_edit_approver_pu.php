<?php
include("connect.php");
require('phpmailer/sendmail.php'); //20240808 add by 04404, new function send mail.
date_default_timezone_set("Asia/Bangkok");

$comment_approver_pu = $_GET["comment_approver_pu"];
if (substr_count($comment_approver_pu, "'") > 0) {
    session_start();
    $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
    $_SESSION['plan_status'] = 'error';
    exit;
}
$t = time();
$date_string = date("Y-m-d H:i:s", $t);
$comment_approver_pu  = '@' . $date_string . ' => ' . $comment_approver_pu;



$quatation_ID = $_GET["quatation_ID"];
$num_req = $_GET["num_req"];
$name_request   = $_GET["name_request"];
$email = $_GET["email"];
$department = $_GET["department"];
$tel = $_GET["tel"];
// $date_picker = $_GET["date_picker"];

$sqlApproverPU = "SELECT * FROM quatation WHERE quatation_ID = '$quatation_ID' ";
$queryApproverPU = sqlsrv_query($conn, $sqlApproverPU);
$resultApproverPU = sqlsrv_fetch_array($queryApproverPU, SQLSRV_FETCH_ASSOC);
$approver_pu_nameTH = $resultApproverPU["approver_pu_nameTH"];
$date_time_stamp = $resultApproverPU["date_time_stamp"];

$sql = "UPDATE quatation SET work_process_status_approvepu = 'unsuccess', status = 5  , comment_approver_pu = '$comment_approver_pu' , date_time_stamp_approver_pu = GETDATE() ,date_time_stamp_update = GETDATE()  WHERE quatation_ID = '$quatation_ID'";
sqlsrv_query($conn, "SET NAMES UTF8");
$query1 = sqlsrv_query($conn, $sql);


if ($query1) {
    date_default_timezone_set("Asia/Bangkok");
    $adate = date("Y-m-d");
    $ldate = date('Y-m-d', strtotime(' - 1 days'));

    $to = $email;
    $subject = 'Alert from System > Request for quotation';
    $fromname = 'ระบบ Request for quotation';
    $message = '<html> 
                                <body style="font-family:Tahoma">
                                <FONT Size = "3"> 
                                
                                <H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
                                <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 
                                   
                                    Dear, คุณ  ' . $name_request . ' <br> <br>
                                        
                                    &nbsp;&nbsp;  ขณะนี้ฝ่ายจัดซื้อ ได้ดำเนินการ<font color="red">ยกเลิก</font> <b>เอกสารเลขที่ : ' . $num_req . '</b><br> 


                                    &nbsp;&nbsp;  ข้อคิดเห็นของ ผู้อนุมัติฝ่ายจัดซื้อ >> <b> ' . $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_approver_pu)) . '</b> <br> <br>

                                      
                                    
                                    &nbsp;&nbsp; <table border="1" cellpadding="0" cellspacing="0"  width="100%" style="font-family:Tahoma;font-size:16px">
                                    <tbody>
                                    <tr>
                                    <td style="border: 2px solid #ccc; border-collapse: collapse;">
                                        <table border="1" cellpadding="0" cellspacing="0"  width="100%" style="font-family:Tahoma;font-size:16px">
                                                <thead>                                        
                                                    <tr>
                                                        <th>Status</th>
                                                        <th width="15%">ผู้ร้องขอ</th>
                                                        <th >อีเมล์</th>
                                                        <th >แผนก</th>
                                                        <th >เบอร์ติดต่อ</th>
                                                        <th >วันที่ขอ</th>
                                                        <th >วันที่อัพเดท</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td >Unsuccess (ไม่สำเร็จ)</td>
                                                    <td >' . $name_request . '</td>
                                                    <td >' . $email . '</td>
                                                    <td >' . $department . '</td>
                                                    <td >' . $tel . '</td>
                                                 
                                                    <td >' . date_format($date_time_stamp, "d/m/Y H:i:s") . '</td>
                                                    <td >' .  date('d/m/Y, H:i:s')  . '</td>
                                                </tbody>
                                            </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                        </table>    
                                        <br> 
                                        &nbsp;&nbsp; คลิกที่ลิงก์เพื่อเข้าใช้งานระบบ <br> 
												
                                        &nbsp;&nbsp; ' . $linkProgram . '<br><br>
                                        
                                        อีเมลฉบับนี้เป็นการแจ้งข้อมูลจากระบบโดยอัตโนมัติ กรุณาอย่าตอบกลับใดๆ หากท่านมีข้อสงสัยหรือต้องการสอบถามรายละเอียดเพิ่มเติมได้ที่อีเมล : TSG.App@Thaisummit.co.th <br>
                                         This email is auto-generated. Please do not reply. If you have further enquiries, please contact email : TSG.App@Thaisummit.co.th <br><br>

                                         <b>Thank you and Best Regards,</b><br>
                                         <b>Information Technology</b>ards,

                                
                                </FONT>
                                </body>
                                </html>';
    $headers = 'From: NoReply.Quotation_request@thaisummit.co.th' . "\r\n" .
        'Reply-To: no-reply@thaisummit.co.th' . "\r\n" .
        'Content-type: text/html; charset=utf-8' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // $Send = mail($to, $subject, $message, $headers);
    // $Send = fncPhpMailer($to, $subject, $message);
    $Send = fncPhpMailer($to, $cc, $bcc, $subject, $message); // add cc, bcc by 04726 on 20260501

    if ($Send) {
        echo "Email Sending";

        session_start();
        $_SESSION['plan'] = "Update Successfully and Email Sending! ";
        header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
        // echo"<script type=\"text/javascript\">alert(\"ไม่สามารถ Update ค่าน้ำมันได้ เมลถูกส่งเรียบร้อยแล้ว!\");</script>";
    } else {
        echo "Email Can Not Send";

        session_start();
        $_SESSION['plan'] = "Update Successfully but Email Can't Send! ";
        header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
        //echo"<script type=\"text/javascript\">alert(\"ไม่สามารถ Update ค่าน้ำมัน และส่งเมลไม่ได้!\");</script>";
    }
} else {
    session_start();
    $_SESSION['plan'] = "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
    header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
}




exit;
//*** Reject user not online

sqlsrv_close($conn);
