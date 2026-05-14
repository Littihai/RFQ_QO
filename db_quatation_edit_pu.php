<?php
include("connect.php");
require('phpmailer/sendmail.php'); //20240808 add by 04404, new function send mail.
date_default_timezone_set("Asia/Bangkok");
switch ($_POST['submit']) {

    case 'submit':

        if (isset($_POST["quatation_ID"])) {

            $comment_pu = $_POST["comment_pu"];
            if (substr_count($comment_pu, "'") > 0) {
                session_start();
                $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
                $_SESSION['plan_status'] = 'error';
                exit;
            }
            $comment_pu_last = $_POST["comment_pu_last"];
            $t = time();
            $date_string = date("Y-m-d H:i:s", $t);

            $comment_pu = $comment_pu_last . '@' . $date_string . ' => ' . $comment_pu;

            $two = str_replace("@", "&#13;&#10;@", $comment_pu);
            echo '$two = ' . $two . '<br>';
            $pos = strpos($two, "&#13;&#10;@");
            echo '$pos = ' . $pos . '<br>';
            if ($pos !== false) {
                $comment_pu = substr_replace($two, '@', $pos, strlen("&#13;&#10;@"));
                // echo $newstring.'<br>';
            }

            $quatation_ID = $_POST["quatation_ID"];

            $num_req = $_POST["num_req"];
            $name_request = $_POST["name_request"];
            $email = $_POST["email"];
            $department = $_POST["department"];
            $tel = $_POST["tel"];
            // $date_picker = $_POST["date_picker"];

            $sqlPU = "Select Top 1 quatation.*, EmpSendMailCCVw.EmailCC From quatation Left Join EmpSendMailCCVw On quatation.tse_rfq_type = TypeId Where quatation.quatation_ID = '$quatation_ID' ";
            $queryPU = sqlsrv_query($conn, $sqlPU);
            $resultPU = sqlsrv_fetch_array($queryPU, SQLSRV_FETCH_ASSOC);
            $date_time_stamp = $resultPU["date_time_stamp"];
            $date_picker = $resultPU["date_picker"];
            $approver_user_code = $resultPU["approver_user_code"];
            $approver_pu_code = $resultPU["approver_pu_code"];
            $approver_pu_nameTH = $resultPU["approver_pu_nameTH"];
            $pu_code = $resultPU["pu_code"];
            $pu_nameTH = $resultPU["pu_nameTH"];
            $tse_rfq_type = $resultPU["tse_rfq_type"];
            $EmailCC = $resultPU["EmailCC"];

             // ===================================== Add function update EndDate_LT when submit by 04726 on 20260401 ============================
               if (isset($_POST["num_req"])) {

                    $RFQNum = trim($_POST["num_req"]);
                    $UpdateBy = $_SESSION['username'];
                    $UpdateDate = date("Y-m-d H:i:s");

                    // 1. UPDATE เฉพาะ 3 คอลัมน์ (เหมือนเดิม)
                    $sql = "UPDATE [TSE_CateLeadTime]
                            SET [EndDate_LT] = GETDATE(),
                                [UpdateBy] = ?,
                                [UpdateDate] = ?
                            WHERE [RFQNum] = ?";

                    $params = array($UpdateBy, $UpdateDate, $RFQNum);
                    $query = sqlsrv_query($conn, $sql, $params);

                    // 2. Call Stored Procedure (คำนวณ Total) แบบเงียบ
                    if ($query !== false) {
                        $call = "{CALL dbo.sp_UpdateLeadTime(?)}";
                        $params2 = array($RFQNum);

                        $stmt = @sqlsrv_query($conn, $call, $params2); // เงียบ
                    }
                }
                // =======================================================================
                
            $sqlEmail = "SELECT * FROM vw_Employee WHERE EmployeeCode = '$approver_user_code' ";
            $queryEmail = sqlsrv_query($conn, $sqlEmail);
            $resultEmail = sqlsrv_fetch_array($queryEmail, SQLSRV_FETCH_ASSOC);
            $email_approver_user = $resultEmail["Email"];

            $sqlEmail1 = "SELECT * FROM vw_Employee WHERE EmployeeCode = '$approver_pu_code' ";
            $queryEmail1 = sqlsrv_query($conn, $sqlEmail1);
            $resultEmail1 = sqlsrv_fetch_array($queryEmail1, SQLSRV_FETCH_ASSOC);
            $email_approver_pu = $resultEmail1["Email"];

            $sqlPuEmail = "SELECT Top 1 * FROM vw_Employee WHERE EmployeeCode = '$pu_code' ";
            $queryPuEmail = sqlsrv_query($conn, $sqlPuEmail);
            $resultPuEmail = sqlsrv_fetch_array($queryPuEmail, SQLSRV_FETCH_ASSOC);
            $email_pu = $resultPuEmail["Email"];
            
            $sqlAppEmail = "SELECT Top 1 * FROM RFQApproverEmailAll Where quatation_ID = '$quatation_ID' ";
            $queryAppEmail = sqlsrv_query($conn, $sqlAppEmail);
            $resultAppEmail = sqlsrv_fetch_array($queryAppEmail, SQLSRV_FETCH_ASSOC);
            $email_approver = $resultAppEmail["ApproverEmail"];
            
            $sql = "UPDATE quatation 
                    SET work_process_status_pu = 'success', 
                    status = 4, 
                    comment_pu = '$comment_pu', 
                    date_time_stamp_pu = GETDATE(), 
                    date_time_stamp_update = GETDATE() 
                    WHERE quatation_ID = '$quatation_ID' ";

            if($tse_rfq_type == "4"){
                $sql = "UPDATE quatation 
                        SET work_process_status_pu = 'success', 
                        comment_pu = '$comment_pu', 
                        date_time_stamp_pu = GETDATE(), 
                        work_process_status_approvepu = 'success', 
                        status = 4, 
                        date_time_stamp_approver_pu_last = GETDATE(), 
                        date_time_stamp_update = GETDATE() 
                        WHERE quatation_ID = '$quatation_ID' ";
            }
                    
            sqlsrv_query($conn, "SET NAMES UTF8");
            $query1 = sqlsrv_query($conn, $sql);

            $target_dir = "upload/";
            $destination = dirname(__FILE__) . '/upload/';
            $countfiles = count($_FILES['files']['name']);
            if (isset($_FILES["files"]["tmp_name"])) {
                for ($key = 0; $key < $countfiles; $key++) {

                    // foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {

                    $temp = $_FILES["files"]["tmp_name"][$key];
                    $tempType = explode(".", $_FILES["files"]["name"][$key]);
                    $tempType = str_replace(" ", "_", $tempType);
                    $newfilename = current($tempType) . '_' . $num_req . '_' . time() . '.' . end($tempType);

                    $type = $_FILES["files"]["type"][$key];
                    if (empty($temp)) {
                        break;
                    }

                    if (file_exists($newfilename)) {
                        break;
                    } else {
                        // move_uploaded_file($temp, $target_dir . $newfilename);
                        move_uploaded_file($temp, $destination . $newfilename);
                        // move_uploaded_file($temp, $target_dir . $newfilename);
                        $EmployeeName = $_POST["EmployeeName"];
                        $sql2 = "INSERT INTO quatation_file (Num_req,File_name,Type,Update_by,Date_create,Date_modified) VALUES ('$num_req','$newfilename'  ,'$type', '$EmployeeName' ,GETDATE() ,GETDATE())";
                        sqlsrv_query($conn, "SET NAMES UTF8");
                        $query2 = sqlsrv_query($conn, $sql2);
                    }
                }
            }
            $email_bcc = 'littichai_y@ts-engineering.com'; //add email by 04726 20260508

            //Add function send mail when submit by 04726 on 20260330
            if ($query1) {
                
                date_default_timezone_set("Asia/Bangkok");
                $adate = date("Y-m-d");
                $ldate = date('Y-m-d', strtotime(' - 1 days'));
                $to = $email;
                
                if($email_approver != ""){
                    $to .= ',' . $email_approver;
                }

                if($email_pu != ""){
                    $to .= ',' . $email_pu;
                }
                $bcc = $email_bcc; // add email by 04726 20260508
                $subject = 'RFQ Completed';

                $message = '<html> 
                                        <body style="font-family:Tahoma">
                                        <FONT Size = "3"> 
                                        
                                        <H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
                                        <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 
                                        
                                            <b>Dear All, ' . '</b> <br> <br> 
                                                
                                            &nbsp;&nbsp; ขณะนี้ฝ่ายจัดซื้อ ได้ดำเนินการอัพเดทราคาสั่งซื้อ<font color="blue">สำเร็จ</font> <b> เอกสารเลขที่ : ' . $num_req . '</b><br>                              
                                                
                                            &nbsp;&nbsp;    ข้อคิดเห็นของ ฝ่ายจัดซื้อ วันเวลา <b> ' . $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_pu)) . '</b> <br> <br>                                          
                                            
                                            &nbsp;&nbsp; <table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
                                            <tbody>
                                            <tr>
                                            <td style="border: 2px solid #ccc; border-collapse: collapse;">
                                                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
                                                        <thead>                                        
                                                            <tr>
                                                                <th width="15%">ผู้ร้องขอ</th>
                                                                <th >อีเมล์</th>
                                                                <th >แผนก</th>
                                                                <th >เบอร์ติดต่อ</th>
                                                                <th >วันที่ขอ</th>
                                                                <th >วันที่อัพเดท</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <td >' . $name_request . '</td>
                                                            <td >' . $email . '</td>
                                                            <td >' . $department . '</td>
                                                            <td >' . $tel . '</td>
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
                    
                $Send = fncPhpMailer($to, $cc, $bcc, $subject, $message); // add cc, bcc by 04726 on 20260501

                session_start();
                $_SESSION['plan'] = $Send 
                    ? "Update Successfully and Email Sending!" 
                    : "Update Successfully but Email Can't Send!";

                header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                exit;
            }
            
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
                                       
                                        <b>Dear, ' . $approver_pu_nameTH . ' </b><br> <br>
                                            
                                        &nbsp;&nbsp;  ขณะนี้ทาง ' . $pu_nameTH . ' ได้ดำเนินการอัพเดทราคา<font color="blue">สำเร็จ</font> เอกสารเลขที่ : ' . $num_req . '</b><br> 

                                        &nbsp;&nbsp;    ข้อคิดเห็นของ ฝ่ายจัดซื้อ วันเวลา <b> ' .  $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_pu)) . '</b> <br> <br>
        
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
                                                        <td >Pending Approver purchase (รออนุมัติ)</td>
                                                        <td >' . $name_request . '</td>
                                                        <td >' . $email . '</td>
                                                        <td >' . $department . '</td>
                                                        <td >' . $tel . '</td>
                                                        <td >' . date_format($date_time_stamp, "d/m/Y H:i:s") . '</td>
                                                        <td >' . date_format($date_picker, "d/m/Y H:i:s") . '</td>
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

                if($tse_rfq_type == "4"){
                    $to = $email;
                    if($email_approver != ""){
                        $to .= ',' . $email_approver . ',' . $email_pu;
                    }else{
                        $to .= ',' . $email_pu;
                    }
                    
                    $message = '<html> 
                                        <body style="font-family:Tahoma">
                                        <FONT Size = "3"> 
                                        
                                        <H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
                                        <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 
                                        
                                            <b>Dear All, ' . '</b> <br> <br> 
                                                
                                            &nbsp;&nbsp; ขณะนี้ฝ่ายจัดซื้อ ได้ดำเนินการอัพเดทราคาสั่งซื้อ<font color="blue">สำเร็จ</font> <b> เอกสารเลขที่ : ' . $num_req . '</b><br>                              
                                                
                                            &nbsp;&nbsp;    ข้อคิดเห็นของ ฝ่ายจัดซื้อ วันเวลา <b> ' . $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_pu)) . '</b> <br> <br>                                          
                                            
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
                }    

                // $Send = mail($to, $subject, $message, $headers);
                // $Send = fncPhpMailer($to, $subject, $message);
                $Send = fncPhpMailer($to, $cc, $bcc, $subject, $message); // add cc, bcc by 04726 on 20260501

                if ($Send) {
                    echo "Email Sending";

                    session_start();
                    $_SESSION['plan'] = "Update Successfully and Email Sending! ";
                    $_SESSION['plan_status'] = "success";
                    header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                    // echo"<script type=\"text/javascript\">alert(\"ไม่สามารถ Update ค่าน้ำมันได้ เมลถูกส่งเรียบร้อยแล้ว!\");</script>";
                } else {
                    echo "Email Can Not Send";

                    session_start();
                    $_SESSION['plan'] = "Update Successfully but Email Can't Send! ";
                    $_SESSION['plan_status'] = "error";
                    header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                    //echo"<script type=\"text/javascript\">alert(\"ไม่สามารถ Update ค่าน้ำมัน และส่งเมลไม่ได้!\");</script>";
                }
            } else {
                session_start();
                $_SESSION['plan'] = "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
                $_SESSION['plan_status'] = "error";
                header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);

                //save_logfile($con,'ADD','Error ADD Contact Person ['.$sql_add_cp.']',$name);
            }
        }
        break;

    case 'cancel':

        $comment_pu = $_POST["comment_pu"];
        if (substr_count($comment_pu, "'") > 0) {
            session_start();
            $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
            $_SESSION['plan_status'] = 'error';
            exit;
        }
        $comment_pu_last = $_POST["comment_pu_last"];
        $t = time();
        $date_string = date("Y-m-d H:i:s", $t);

        $comment_pu = $comment_pu_last . '@' . $date_string . ' => ' . $comment_pu;

        $two = str_replace("@", "&#13;&#10;@", $comment_pu);
        echo '$two = ' . $two . '<br>';
        $pos = strpos($two, "&#13;&#10;@");
        echo '$pos = ' . $pos . '<br>';
        if ($pos !== false) {
            $comment_pu = substr_replace($two, '@', $pos, strlen("&#13;&#10;@"));
            // echo $newstring.'<br>';
        }

        $quatation_ID = $_POST["quatation_ID"];
        $num_req = $_POST["num_req"];
        $name_request   = $_POST["name_request"];
        $email = $_POST["email"];
        $department = $_POST["department"];
        $tel = $_POST["tel"];

        $sqlPU = "SELECT * FROM quatation WHERE quatation_ID = '$quatation_ID' ";
        $queryPU = sqlsrv_query($conn, $sqlPU);
        $resultPU = sqlsrv_fetch_array($queryPU, SQLSRV_FETCH_ASSOC);
        $date_time_stamp = $resultPU["date_time_stamp"];
        $date_picker = $resultPU["date_picker"];
        $approver_user_code = $resultPU["approver_user_code"];
        $approver_user_nameTH = $resultPU["approver_user_nameTH"];
        $approver_pu_code = $resultPU["approver_pu_code"];
        $approver_pu_nameTH = $resultPU["approver_pu_nameTH"];

        $sqlEmail = "SELECT * FROM vw_Employee WHERE EmployeeCode = '$approver_user_code' ";
        $queryEmail = sqlsrv_query($conn, $sqlEmail);
        $resultEmail = sqlsrv_fetch_array($queryEmail, SQLSRV_FETCH_ASSOC);
        $email_approver_user = $resultEmail["Email"];

        $sqlAppEmail = "SELECT Top 1 * FROM RFQApproverEmailAll Where quatation_ID = '$quatation_ID' ";
        $queryAppEmail = sqlsrv_query($conn, $sqlAppEmail);
        $resultAppEmail = sqlsrv_fetch_array($queryAppEmail, SQLSRV_FETCH_ASSOC);
        $email_approver = $resultAppEmail["ApproverEmail"];

        $sqlEmail1 = "SELECT * FROM vw_Employee WHERE EmployeeCode = '$approver_pu_code' ";
        $queryEmail1 = sqlsrv_query($conn, $sqlEmail1);
        $resultEmail1 = sqlsrv_fetch_array($queryEmail1, SQLSRV_FETCH_ASSOC);
        $email_approver_pu = $resultEmail1["Email"];

        $sql = "";
        if ($_POST["status"] == '7') {
            $sql = "UPDATE quatation SET work_process_status_approvepu = 'success', work_process_status_pu = 'unsuccess',  status = 5 , comment_pu = '$comment_pu' , date_time_stamp_pu = GETDATE() ,date_time_stamp_update = GETDATE()  WHERE quatation_ID = '$quatation_ID'";
        } else {
            $sql = "UPDATE quatation SET work_process_status_pu = 'unsuccess', status = 5 , comment_pu = '$comment_pu' , date_time_stamp_pu = GETDATE() ,date_time_stamp_update = GETDATE()  WHERE quatation_ID = '$quatation_ID'";
        }

        sqlsrv_query($conn, "SET NAMES UTF8");
        $query1 = sqlsrv_query($conn, $sql);
        if ($query1) {
            date_default_timezone_set("Asia/Bangkok");
            $adate = date("Y-m-d");
            $ldate = date('Y-m-d', strtotime(' - 1 days'));

            $to = $email;
            if($email_approver != ""){
                $to .= ',' . $email_approver . ',' . $email_approver_pu;
            }else{
                $to .= ',' . $email_approver_pu;
            }
            $subject = 'Alert from System > Request for quotation';
            $fromname = 'ระบบ Request for quotation';
            $message = '<html> 
                                    <body style="font-family:Tahoma">
                                    <FONT Size = "3"> 
                                    
                                        <H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
                                        <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3>
                                       
                                        <b>Dear All, ' . ' </b><br> <br>
                                            
                                        &nbsp;&nbsp; ขณะนี้ ฝ่ายจัดซื้อ ได้ดำเนินการ<font color="red">ยกเลิก</font> <b>เอกสารเลขที่ : ' . $num_req . '</b><br> 


                                        &nbsp;&nbsp; ข้อคิดเห็นของ ฝ่ายจัดซื้อ วันเวลา <b> ' .  $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_pu)) . '</b> <br> <br>
                                          
                                        
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
                                                        <td >Unsuccess (ไม่สำเร็จ)</td>
                                                        <td >' . $name_request . '</td>
                                                        <td >' . $email . '</td>
                                                        <td >' . $department . '</td>
                                                        <td >' . $tel . '</td>
                                                        <td >' . date_format($date_time_stamp, "d/m/Y H:i:s") . '</td>
                                                        <td >' . date_format($date_picker, "d/m/Y H:i:s") . '</td>
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

    case 'change':

        $comment_pu = $_POST["comment_pu"];
        if (substr_count($comment_pu, "'") > 0) {
            session_start();
            $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
            $_SESSION['plan_status'] = 'error';
            exit;
        }
        $comment_pu_last = $_POST["comment_pu_last"];
        $t = time();
        $date_string = date("Y-m-d H:i:s", $t);

        $comment_pu = $comment_pu_last . '@' . $date_string . ' => ' . $comment_pu;

        $two = str_replace("@", "&#13;&#10;@", $comment_pu);
        echo '$two = ' . $two . '<br>';
        $pos = strpos($two, "&#13;&#10;@");
        echo '$pos = ' . $pos . '<br>';
        if ($pos !== false) {
            $comment_pu = substr_replace($two, '@', $pos, strlen("&#13;&#10;@"));
            // echo $newstring.'<br>';
        }

        $quatation_ID = $_POST["quatation_ID"];
        $num_req = $_POST["num_req"];
        $name_request   = $_POST["name_request"];
        $email = $_POST["email"];
        $department = $_POST["department"];
        $tel = $_POST["tel"];

        $sqlPU = "SELECT * FROM quatation WHERE quatation_ID = '$quatation_ID' ";
        $queryPU = sqlsrv_query($conn, $sqlPU);
        $resultPU = sqlsrv_fetch_array($queryPU, SQLSRV_FETCH_ASSOC);
        $date_time_stamp = $resultPU["date_time_stamp"];
        $approver_user_code = $resultPU["approver_user_code"];
        $approver_gm_code = $resultPU["approver_gm_code"];
        $approver_md_code = $resultPU["approver_md_code"];
        $approver_pu_code = $resultPU["approver_pu_code"];
        $approver_pu_nameTH = $resultPU["approver_pu_nameTH"];
        $pu_nameTH = $resultPU["pu_nameTH"];

        $_status = 2;
        if($approver_md_code != ""){
            $_status = 10;
        }else if($approver_gm_code != ""){
            $_status = 9;
        }

        $sqlEmail = "SELECT * FROM vw_Employee WHERE EmployeeCode = '$approver_user_code' ";
        $queryEmail = sqlsrv_query($conn, $sqlEmail);
        $resultEmail = sqlsrv_fetch_array($queryEmail, SQLSRV_FETCH_ASSOC);
        $email_approver_user = $resultEmail["Email"];

        $sqlEmail1 = "SELECT * FROM vw_Employee WHERE EmployeeCode = '$approver_pu_code' ";
        $queryEmail1 = sqlsrv_query($conn, $sqlEmail1);
        $resultEmail1 = sqlsrv_fetch_array($queryEmail1, SQLSRV_FETCH_ASSOC);
        $email_approver_pu = $resultEmail1["Email"];

        //Add function send email to manager purchase when change purchase staff by 04726 on 20260513
        $sqlEmail2 = "SELECT * FROM vw_Employee WHERE EmployeeCode = '01294' ";
        $queryEmail2 = sqlsrv_query($conn, $sqlEmail2);
        $resultEmail2 = sqlsrv_fetch_array($queryEmail2, SQLSRV_FETCH_ASSOC);
        $email_manager_pu = $resultEmail2["Email"];

        $sqlPosition = "SELECT * FROM admin_purchase WHERE Position = 'ASST.SECTION MGR.' ";
        $queryPosition = sqlsrv_query($conn, $sqlPosition);
        $resultPosition = sqlsrv_fetch_array($queryPosition, SQLSRV_FETCH_ASSOC);
        $Manager_Code = $resultPosition["EmployeeCode"];
        $Manager_FullName = $resultPosition["ThFullName"];

         // ===================================== Add function update Lead Time when Change by 04726 on 20260401 ============================
               if (isset($_POST["num_req"])) {
                $RFQNum        = trim($_POST["num_req"]);
                $LT_UpdateBy   = $approver_pu_code; // ดึงจาก query quatation ที่ทำไปก่อนหน้าแล้ว
                $LT_UpdateDate = date("Y-m-d H:i:s");

                $sqlLT    = "UPDATE [TSE_CateLeadTime]
                            SET [UpdateBy]   = ?,
                                [UpdateDate] = ?
                            WHERE [RFQNum]   = ?";

                $paramsLT = array($LT_UpdateBy, $LT_UpdateDate, $RFQNum);
                $queryLT  = sqlsrv_query($conn, $sqlLT, $paramsLT);

                if ($queryLT !== false) {
                    $callLT    = "{CALL dbo.sp_UpdateLeadTime(?)}";
                    $paramsLT2 = array($RFQNum);
                    @sqlsrv_query($conn, $callLT, $paramsLT2);
                }
            }
                // =======================================================================

        //Update by 04726 on 20260330 change status to pending and send email to approver purchase when change purchase staff
        $sql = "UPDATE quatation SET work_process_status_user = 'pending',work_process_status_approvepu = 'pending',work_process_status_pu = 'wait',status = $_status ,approver_user_code = '$Manager_Code',approver_user_nameTH = '$Manager_FullName', date_time_stamp_pu = GETDATE(),date_time_stamp_update = GETDATE() , comment_pu = '$comment_pu' WHERE quatation_ID = '$quatation_ID'";
        $email_bcc = 'littichai_y@ts-engineering.com'; //add email by 04726 20260508

        sqlsrv_query($conn, "SET NAMES UTF8");
        $query1 = sqlsrv_query($conn, $sql);

        if ($query1) {
            date_default_timezone_set("Asia/Bangkok");
            $adate = date("Y-m-d");
            $ldate = date('Y-m-d', strtotime(' - 1 days'));

            // $to = $email_manager_pu;
            $bcc = $email_bcc; // add email by 04726 20260508
            $subject = 'Alert from System > Request Change Buyer';
            $fromname = 'ระบบ Request for quotation';
            $message = '<html> 
                                                <body style="font-family:Tahoma">
                                                <FONT Size = "3"> 
                                                
                                                <H3><font color="blue">Request Change Buyer (RFQ Online)</font></H3> 
                                                <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 
                                                   
                                                    <b>Dear, '  . $Manager_FullName . '</b> <br> <br>  
                                                    

                                                    &nbsp;&nbsp; <b>'  . $pu_nameTH . '</b> ได้ดำเนินการร้องขอให้ <font color="red">เปลี่ยนผู้ดำเนินการใหม่ </font> <b>เอกสารเลขที่ : ' . $num_req . '</b><br>  
                    
                                                    &nbsp;&nbsp;    ข้อคิดเห็นของ ฝ่ายจัดซื้อ วันเวลา <b> ' .  $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_pu)) . '</b> <br> <br>
                                                      
                                                    
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

            //save_logfile($con,'ADD','Error ADD Contact Person ['.$sql_add_cp.']',$name);
        }

        break;
}

exit;
//*** Reject user not online

sqlsrv_close($conn);
