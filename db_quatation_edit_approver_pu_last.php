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
            $comment_approver_pu_last = $_POST["comment_approver_pu_last"];
            $t = time();
            $date_string = date("Y-m-d H:i:s", $t);
            $comment_approver_pu = $comment_approver_pu_last . '@' . $date_string . ' => ' . $comment_approver_pu;

            $two = str_replace("@", "&#13;&#10;@", $comment_approver_pu);
            echo '$two = ' . $two . '<br>';
            $pos = strpos($two, "&#13;&#10;@");
            echo '$pos = ' . $pos . '<br>';
            if ($pos !== false) {
                $comment_approver_pu = substr_replace($two, '@', $pos, strlen("&#13;&#10;@"));
                // echo $newstring.'<br>';
            }

            $quatation_ID = $_POST["quatation_ID"];
            $num_req = $_POST["num_req"];
            $name_request   = $_POST["name_request"];
            $email = $_POST["email"];
            $department = $_POST["department"];
            $tel = $_POST["tel"];
            // $datepicker = $_POST["datepicker"];

            // $date = $_POST["datepicker"];
            // $time = strtotime($date);
            // $datepicker = date('Ymd', $time);

            $sqlApproverPU = "Select Top 1 quatation.*, EmpSendMailCCVw.EmailCC From quatation Left Join EmpSendMailCCVw On quatation.tse_rfq_type = TypeId Where quatation.quatation_ID = '$quatation_ID' ";
            $queryApproverPU = sqlsrv_query($conn, $sqlApproverPU);
            $resultApproverPU = sqlsrv_fetch_array($queryApproverPU, SQLSRV_FETCH_ASSOC);
            $approver_pu_nameTH = $resultApproverPU["approver_pu_nameTH"];
            $pu_nameTH = $resultApproverPU["pu_nameTH"];
            $approver_user_code = $resultApproverPU["approver_user_code"];
            $date_time_stamp = $resultApproverPU["date_time_stamp"];
            $email = $resultApproverPU["email"];
            $EmailCC = $resultApproverPU["EmailCC"];

            $pu_code = $_POST["pu_code"];

            $sqlpu = "SELECT * FROM vw_Employee where EmployeeCode = '$pu_code' ";
            $querypu = sqlsrv_query($conn, $sqlpu);
            $resultpu = sqlsrv_fetch_array($querypu, SQLSRV_FETCH_ASSOC);
            $email_pu = $resultpu["Email"];

            $sqlEmail = "SELECT * FROM vw_Employee WHERE EmployeeCode = '$approver_user_code' ";
            $queryEmail = sqlsrv_query($conn, $sqlEmail);
            $resultEmail = sqlsrv_fetch_array($queryEmail, SQLSRV_FETCH_ASSOC);
            $email_approver_user = $resultEmail["Email"];
            $approver_user_nameTH = $resultEmail["ThFullName"];

            $sqlAppEmail = "SELECT Top 1 * FROM RFQApproverEmailAll Where quatation_ID = '$quatation_ID' ";
            $queryAppEmail = sqlsrv_query($conn, $sqlAppEmail);
            $resultAppEmail = sqlsrv_fetch_array($queryAppEmail, SQLSRV_FETCH_ASSOC);
            $email_approver = $resultAppEmail["ApproverEmail"];

            $sql = "UPDATE quatation SET work_process_status_approvepu = 'success', status = 4 , comment_approver_pu = '$comment_approver_pu' , date_time_stamp_approver_pu_last = GETDATE(),date_time_stamp_update = GETDATE()  WHERE quatation_ID = '$quatation_ID'";

            sqlsrv_query($conn, "SET NAMES UTF8");
            $query1 = sqlsrv_query($conn, $sql);

            if ($query1) {

                date_default_timezone_set("Asia/Bangkok");
                $adate = date("Y-m-d");
                $ldate = date('Y-m-d', strtotime(' - 1 days'));

                $to = $email;
                if($email_approver != ""){
                    $to .= ',' . $email_approver . ',' . $email_pu;
                }else{
                    $to .= ',' . $email_pu;
                }
                
                $subject = 'Alert from System > Request for quotation';
                $fromname = 'ระบบ Request for quotation';
                $message = '<html> 
                                    <body style="font-family:Tahoma">
                                    <FONT Size = "3"> 
                                    
                                    <H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
                                    <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 
                                       
                                        <b>Dear All, ' . '</b> <br> <br> 
                                            
                                        &nbsp;&nbsp; ขณะนี้ฝ่ายจัดซื้อ ได้ดำเนินการอัพเดทราคาสั่งซื้อ<font color="blue">สำเร็จ</font> <b> เอกสารเลขที่ : ' . $num_req . '</b><br>                              
                                            
                                        &nbsp;&nbsp;    ข้อคิดเห็นของ ฝ่ายจัดซื้อ วันเวลา <b> ' . $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_approver_pu)) . '</b> <br> <br>                                          
                                        
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
                                                            <th >วันที่คาดหวัง</th>
                                                            <th >วันที่ขอ</th>
                                                            <th >วันที่อัพเดท</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <td >สำเร็จ</td>
                                                        <td >' . $name_request . '</td>
                                                        <td >' . $email . '</td>
                                                        <td >' . $department . '</td>
                                                        <td >' . $tel . '</td>
                                                        <td >' . date('d/m/Y H:i:s', $time)  . '</td>
                                                        <td >' . date_format($date_time_stamp, "d/m/Y H:i:s") . '</td>
                                                        <td >' . date('d/m/Y, H:i:s')  . '</td>
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
                $headers = 'From: NoReply.RFQ@ts-engineering.com' . "\r\n";
                if($EmailCC != ""){
                    $headers .= 'CC: '. $EmailCC . "\r\n";
                }
                // 'Reply-To: no-reply@ts-engineering.com' . "\r\n" .
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n" . 
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
                $_SESSION['plan_status'] = 'error';
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
        $comment_approver_pu_last = $_POST["comment_approver_pu_last"];
        $t = time();
        $date_string = date("Y-m-d H:i:s", $t);
        $comment_approver_pu = $comment_approver_pu_last . '@' . $date_string . ' => ' . $comment_approver_pu;

        $two = str_replace("@", "&#13;&#10;@", $comment_approver_pu);
        echo '$two = ' . $two . '<br>';
        $pos = strpos($two, "&#13;&#10;@");
        echo '$pos = ' . $pos . '<br>';
        if ($pos !== false) {
            $comment_approver_pu = substr_replace($two, '@', $pos, strlen("&#13;&#10;@"));
            // echo $newstring.'<br>';
        }

        $quatation_ID = $_POST["quatation_ID"];
        $num_req = $_POST["num_req"];
        $name_request   = $_POST["name_request"];
        $department = $_POST["department"];
        $tel = $_POST["tel"];

        $email =  $_POST["email"];

        $sqlApproverPU = "SELECT * FROM quatation WHERE quatation_ID = '$quatation_ID' ";
        $queryApproverPU = sqlsrv_query($conn, $sqlApproverPU);
        $resultApproverPU = sqlsrv_fetch_array($queryApproverPU, SQLSRV_FETCH_ASSOC);
        $approver_pu_nameTH = $resultApproverPU["approver_pu_nameTH"];
        $pu_nameTH = $resultApproverPU["pu_nameTH"];
        $date_time_stamp = $resultApproverPU["date_time_stamp"];

        $pu_code = $_POST["pu_code"];

        $sqlpu = "SELECT * FROM vw_Employee where EmployeeCode = '$pu_code' ";
        $querypu = sqlsrv_query($conn, $sqlpu);
        $resultpu = sqlsrv_fetch_array($querypu, SQLSRV_FETCH_ASSOC);
        $email_pu = $resultpu["Email"];

        $sql = "UPDATE quatation SET work_process_status_approvepu = 'unsuccess', status = 7  , comment_approver_pu = '$comment_approver_pu' , date_time_stamp_approver_pu_last = GETDATE() ,date_time_stamp_update = GETDATE()  WHERE quatation_ID = '$quatation_ID'";
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
                                   
                                <b>Dear, ' . $pu_nameTH . ' </b><br> <br>
                                        
                                    &nbsp;&nbsp; เนื่องจาก ' . $approver_pu_nameTH . ' ได้ดำเนินการ<font color="red">ไม่อนุมัติ</font><b> เอกสารเลขที่ : ' . $num_req . '</b><br> 

                                        
                                    &nbsp;&nbsp; ข้อคิดเห็นของ ผู้อนุมัติฝ่ายจัดซื้อ วันเวลา <b> ' . $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_approver_pu)) . '</b> <br> <br>
                            
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
                                                    <td >Disapproval by Manager Purchase (ให้ Purchase Staff Revise ใหม่)</td>
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
            $_SESSION['plan_status'] = 'error';
            header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);



            //save_logfile($con,'ADD','Error ADD Contact Person ['.$sql_add_cp.']',$name);
        }
        break;
}


exit;
//*** Reject user not online

sqlsrv_close($conn);
