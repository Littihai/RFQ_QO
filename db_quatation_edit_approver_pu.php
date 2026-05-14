<?php
include("connect.php");
require('phpmailer/sendmail.php'); //20240808 add by 04404, new function send mail.
date_default_timezone_set("Asia/Bangkok");
switch ($_POST['submit']) {
    case 'submit':
        if (isset($_POST["quatation_ID"])) {
            $comment_approver_pu = $_POST["comment_approver_pu"];
            if (substr_count($comment_approver_pu, "'") > 0) {
                session_start();
                $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
                $_SESSION['plan_status'] = 'error';
                exit;
            }

            $t = time();
            $date_string = date("Y-m-d H:i:s", $t);
            $comment_approver_pu  = '@' . $date_string . ' => ' . $comment_approver_pu;

            $quatation_ID = $_POST["quatation_ID"];
            $num_req = $_POST["num_req"];
            $name_request   = $_POST["name_request"];
            $email = $_POST["email"];
            $department = $_POST["department"];
            $tel = $_POST["tel"];
            // $date_picker = $_POST["date_picker"];

            $date = $_POST["datepicker"];
            $time = strtotime($date);
            $datepicker = date('Ymd', $time);

            $sqlApproverPU = "SELECT * FROM quatation WHERE quatation_ID = '$quatation_ID' ";
            $queryApproverPU = sqlsrv_query($conn, $sqlApproverPU);
            $resultApproverPU = sqlsrv_fetch_array($queryApproverPU, SQLSRV_FETCH_ASSOC);
            $approver_pu_nameTH = $resultApproverPU["approver_pu_nameTH"];
            $date_time_stamp = $resultApproverPU["date_time_stamp"];

            $pu_code = $_POST["pu_code"];

            $sqlpu = "SELECT * FROM vw_Employee where EmployeeCode = '$pu_code' ";
            $querypu = sqlsrv_query($conn, $sqlpu);
            $resultpu = sqlsrv_fetch_array($querypu, SQLSRV_FETCH_ASSOC);
            $pu_nameTH = $resultpu["ThFullName"];
            $email_pu = $resultpu["Email"];

            $sql = "UPDATE quatation SET work_process_status_approvepu = 'success',work_process_status_pu = 'pending',status = 3 , pu_code = '$pu_code' , pu_nameTH = '$pu_nameTH' , comment_approver_pu = '$comment_approver_pu' , date_time_stamp_approver_pu = GETDATE(),date_time_stamp_update = GETDATE(), date_picker = '$datepicker'  WHERE quatation_ID = '$quatation_ID'";

            sqlsrv_query($conn, "SET NAMES UTF8");
            $query1 = sqlsrv_query($conn, $sql);
            if ($query1) {

                date_default_timezone_set("Asia/Bangkok");
                $adate = date("Y-m-d");
                $ldate = date('Y-m-d', strtotime(' - 1 days'));

                $to = $email_pu;
                $subject = 'Alert from System > Request for quotation';
                $fromname = 'ระบบ Request for quotation';
                $message = '<html> 
                                    <body style="font-family:Tahoma">
                                    <FONT Size = "3"> 
                                    
                                        <H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
                                        <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 
                                       
                                        <b>Dear, ' . $pu_nameTH . '</b> <br> <br>
                                            
                                        &nbsp;&nbsp;  ขณะนี้ ' . $approver_pu_nameTH . ' ได้ดำเนินการ <font color="blue">Approve และเลือกให้คุณเป็นผู้ดำเนินการต่อ</font> <b>เอกสารเลขที่ : ' . $num_req . '</b><br> 
                                              
                                        &nbsp;&nbsp;  ข้อคิดเห็นของ ผู้อนุมัติฝ่ายจัดซื้อ วันเวลา <b> ' .  $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_approver_pu)) . '</b> <br> <br>
                                        
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
                                                            <th >วันที่ต้องการ</th>
                                                            <th >วันที่อัพเดท</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <td >Approved by Manager purchase (ผจก. สั่งซื้ออนุมัติแล้ว)</td>
                                                        <td >' . $name_request . '</td>
                                                        <td >' . $email . '</td>
                                                        <td >' . $department . '</td>
                                                        <td >' . $tel . '</td>
                                                        <td >' . date_format($date_time_stamp, "d/m/Y H:i:s") . '</td>
                                                        <td >' . date('d/m/Y H:i:s', $time) . '</td>
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
        }
        break;

    case 'cancel':
        $comment_approver_pu = $_POST["comment_approver_pu"];
        if (substr_count($comment_approver_pu, "'") > 0) {
            session_start();
            $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
            $_SESSION['plan_status'] = 'error';
            exit;
        }

        $t = time();
        $date_string = date("Y-m-d H:i:s", $t);
        $comment_approver_pu  = '@' . $date_string . ' => ' . $comment_approver_pu;

        $quatation_ID = $_POST["quatation_ID"];
        $num_req = $_POST["num_req"];
        $name_request   = $_POST["name_request"];
        $email = $_POST["email"];
        $department = $_POST["department"];
        $tel = $_POST["tel"];
        // $date_picker = $_GET["date_picker"];

        $sqlApproverUser = "SELECT * FROM quatation WHERE quatation_ID = '$quatation_ID' ";
        $queryApproverUser = sqlsrv_query($conn, $sqlApproverUser);
        $resultApproverUser = sqlsrv_fetch_array($queryApproverUser, SQLSRV_FETCH_ASSOC);
        $approver_user_code = $resultApproverUser["approver_user_code"];
        $approver_user_nameTH = $resultApproverUser["approver_user_nameTH"];
        $approver_gm_code = $resultApproverUser["approver_gm_code"];
        $approver_gm_nameTH = $resultApproverUser["approver_gm_nameTH"];
        $approver_md_code = $resultApproverUser["approver_md_code"];
        $approver_md_nameTH = $resultApproverUser["approver_md_nameTH"];

        $date_time_stamp = $resultApproverUser["date_time_stamp"];

        $sqlAppEmail = "SELECT Top 1 * FROM RFQApproverEmailAll Where quatation_ID = '$quatation_ID' ";
        $queryAppEmail = sqlsrv_query($conn, $sqlAppEmail);
        $resultAppEmail = sqlsrv_fetch_array($queryAppEmail, SQLSRV_FETCH_ASSOC);
        $email_approver = $resultAppEmail["ApproverEmail"];

        $sql = "UPDATE quatation SET work_process_status_approvepu = 'unsuccess', status = 5  , comment_approver_pu = '$comment_approver_pu' , date_time_stamp_approver_pu = GETDATE() ,date_time_stamp_update = GETDATE()  WHERE quatation_ID = '$quatation_ID'";
        sqlsrv_query($conn, "SET NAMES UTF8");
        $query1 = sqlsrv_query($conn, $sql);

        if ($query1) {
            date_default_timezone_set("Asia/Bangkok");
            $adate = date("Y-m-d");
            $ldate = date('Y-m-d', strtotime(' - 1 days'));

            $to = $email;
            if($email_approver != ""){
                $to .= ',' . $email_approver;
            }
            $subject = 'Alert from System > Request for quotation';
            $fromname = 'ระบบ Request for quotation';
            $message = '<html> 
                                <body style="font-family:Tahoma">
                                <FONT Size = "3"> 
                                
                                <H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
                                <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 
                                   
                                    Dear All, ' . ' <br> <br>
                                        
                                    &nbsp;&nbsp;  ขณะนี้ฝ่ายจัดซื้อ ได้ดำเนินการ<font color="red">ยกเลิก</font> <b>เอกสารเลขที่ : ' . $num_req . '</b><br> 

                                    &nbsp;&nbsp;  ข้อคิดเห็นของ ผู้อนุมัติฝ่ายจัดซื้อ วันเวลา <b> ' . $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_approver_pu)) . '</b> <br> <br>                                      
                                    
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
        }

        exit;
        //*** Reject user not online
}
sqlsrv_close($conn);
