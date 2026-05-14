<?php
include("connect.php");
require('phpmailer/sendmail.php'); //20240808 add by 04404, new function send mail.
date_default_timezone_set("Asia/Bangkok");

$quatation_ID = $_GET["quatation_ID"];
$num_req = $_GET["num_req"];
$name_request   = $_GET["name_request"];
$email = $_GET["email"];
$department = $_GET["department"];
$tel = $_GET["tel"];

$sqlPU = "SELECT * FROM quatation WHERE quatation_ID = '$quatation_ID' ";
$queryPU = sqlsrv_query($conn, $sqlPU);
$resultPU = sqlsrv_fetch_array($queryPU, SQLSRV_FETCH_ASSOC);
$date_time_stamp = $resultPU["date_time_stamp"];
$approver_user_code = $resultPU["approver_user_code"];
$approver_pu_code = $resultPU["approver_pu_code"];
$approver_pu_nameTH = $resultPU["approver_pu_nameTH"];

$sqlEmail = "SELECT * FROM vw_Employee WHERE EmployeeCode = '$approver_user_code' ";
$queryEmail = sqlsrv_query($conn, $sqlEmail);
$resultEmail = sqlsrv_fetch_array($queryEmail, SQLSRV_FETCH_ASSOC);
$email_approver_user = $resultEmail["Email"];

$sqlEmail1 = "SELECT * FROM vw_Employee WHERE EmployeeCode = '$approver_pu_code' ";
$queryEmail1 = sqlsrv_query($conn, $sqlEmail1);
$resultEmail1 = sqlsrv_fetch_array($queryEmail1, SQLSRV_FETCH_ASSOC);
$email_approver_pu = $resultEmail1["Email"];

$sql = "UPDATE quatation SET work_process_status_user = 'success',work_process_status_approvepu = 'pending',work_process_status_pu = 'wait',status = 2 , date_time_stamp_pu = GETDATE(),date_time_stamp_update = GETDATE() WHERE quatation_ID = '$quatation_ID'";

sqlsrv_query($conn, "SET NAMES UTF8");
$query1 = sqlsrv_query($conn, $sql);

if ($query1) {
    date_default_timezone_set("Asia/Bangkok");
    $adate = date("Y-m-d");
    $ldate = date('Y-m-d', strtotime(' - 1 days'));

    $to = $email_approver_pu;
    $subject = 'Alert from System > Request for quotation';
    $fromname = 'ระบบ Request for quotation';
    $message = '<html> 
                                    <body style="font-family:Tahoma">
                                    <FONT Size = "3"> 
                                    
                                    <H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
                                    <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 
                                       
                                        <b>Dear, '  . $approver_pu_nameTH . '</b> <br> <br>
                                            
                                        &nbsp;&nbsp; ขณะนี้ Purchase staff ได้ดำเนินการร้องขอให้<font color="red">เปลี่ยนผู้ดำเนินการใหม่ </font> <b>เอกสารเลขที่ : ' . $num_req . '</b><br>                              
                                            
                                        &nbsp;&nbsp;    ข้อคิดเห็นของ Purchase staff >> <b> ' . $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_pu)) . '</b> <br> <br>                                          
                                        
                                        &nbsp;&nbsp; <table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
                                        <tbody>
                                        <tr>
                                        <td style="border: 2px solid #ccc; border-collapse: collapse;">
                                            <table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
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
                                                        <td >Change Purchase Staff</td>
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
                                            
                                            NOTICE: This mail is automatic from system. Please do not reply to this note.<br>
                                    
                                    </FONT>
                                    </body>
                                    </html>';
    $headers = 'From: NoReply.RFQ@ts-engineering.com' . "\r\n" .
        // 'Reply-To: no-reply@ts-engineering.com' . "\r\n" .
        'Content-type: text/html; charset=utf-8' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // $Send = mail($to, $subject, $message, $headers);
    $Send = fncPhpMailer($to, $subject, $message);

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
    //save_logfile($con,'ADD','Error ADD Contact Person ['.$sql_add_cp.']',$name);
}

//*** Reject user not online

sqlsrv_close($conn);
