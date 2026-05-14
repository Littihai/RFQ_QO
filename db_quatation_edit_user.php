<?php
include("connect.php");
require('phpmailer/sendmail.php'); //20240808 add by 04404, new function send mail.
// ใช้สำหรับ ดูค่าต่างๆ
error_reporting(~E_NOTICE);
date_default_timezone_set("Asia/Bangkok");

header('Content-Type: text/html; charset=UTF-8');

if (isset($_POST["quatation_ID"])) {

    // ใช้สำหรับ ดูค่าต่างๆ
    // echo "<pre>";
    // print_r($_POST);
    // print_r($_FILES);
    // echo "</pre>";

    $comment_user = $_POST["comment_user"];
    if (substr_count($comment_user, "'") > 0) {
        session_start();
        $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
        $_SESSION['plan_status'] = 'error';
        exit;
    }
    $comment_user_last = $_POST["comment_user_last"];
    $t = time();
    $date_string = date("Y-m-d H:i:s", $t);

    $comment_user = $comment_user_last . '@' . $date_string . ' => ' . $comment_user;

    $two = str_replace("@", "&#13;&#10;@", $comment_user);
    echo '$two = ' . $two . '<br>';
    $pos = strpos($two, "&#13;&#10;@");
    echo '$pos = ' . $pos . '<br>';
    if ($pos !== false) {
        $comment_user = substr_replace($two, '@', $pos, strlen("&#13;&#10;@"));
        // echo $newstring.'<br>';
    }


    $quatation_ID = $_POST["quatation_ID"];

    $num_req = $_POST["num_req"];
    $name_request = $_POST["name_request"];
    $email = $_POST["email"];
    $department = $_POST["department"];
    $tel = $_POST["tel"];
    $date_picker = $_POST["date_picker"];
    $rfq_type_edit = $_POST["rfq_type_edit"];

    $sqlUser = "SELECT * FROM quatation WHERE quatation_ID = '$quatation_ID' ";
    $queryUser = sqlsrv_query($conn, $sqlUser);
    $resultUser = sqlsrv_fetch_array($queryUser, SQLSRV_FETCH_ASSOC);
    $date_time_stamp = $resultUser["date_time_stamp"];
    $approver_user_code = $resultUser["approver_user_code"];
    $approver_user_nameTH = $resultUser["approver_user_nameTH"];
    $approver_gm_code = $resultUser['approver_gm_code'];
    $approver_gm_nameTH = $resultUser['approver_gm_nameTH'];
    $approver_md_code = $resultUser['approver_md_code'];
    $approver_md_nameTH = $resultUser['approver_md_nameTH'];

    $work_process_status_user = $resultUser['work_process_status_user'];
    $work_process_status_gm = $resultUser['work_process_status_gm'];
    $work_process_status_md = $resultUser['work_process_status_md'];

    $approver_code = "";
    $approver_nameTH = "";

    if($work_process_status_user == "success" || $work_process_status_user == "unsuccess"){
        $work_process_status_user = "pending";
        $approver_code = $approver_user_code;
        $approver_nameTH = $approver_user_nameTH;
    }else if($work_process_status_gm == "success" || $work_process_status_gm == "unsuccess"){
        $work_process_status_gm = "pending";
        $approver_code = $approver_gm_code;
        $approver_nameTH = $approver_gm_nameTH;
    }else if($work_process_status_md == "success" || $work_process_status_md == "unsuccess"){
        $work_process_status_md = "pending";
        $approver_code = $approver_md_code;
        $approver_nameTH = $approver_md_nameTH;
    }

    $_strSql = "";
    if($work_process_status_user == "success" || $work_process_status_user == "unsuccess"){
        $work_process_status_user = "wait";
    }
    if($work_process_status_gm == "success" || $work_process_status_gm == "unsuccess"){
        $work_process_status_gm = "wait";
        $_strSql .= "approver_gm_nameTH = NULL, ";
    }
    if($work_process_status_md == "success" || $work_process_status_md == "unsuccess"){
        $work_process_status_md = "wait";
        $_strSql .= "approver_md_nameTH = NULL, ";
    }

    $sqlEmail = "SELECT * FROM vw_Employee WHERE EmployeeCode = '$approver_code' ";
    $queryEmail = sqlsrv_query($conn, $sqlEmail);
    $resultEmail = sqlsrv_fetch_array($queryEmail, SQLSRV_FETCH_ASSOC);
    $email_approver_user = $resultEmail["Email"];
    
    $sql = "UPDATE quatation 
            SET 
            work_process_status_user = '$work_process_status_user', 
            work_process_status_gm = '$work_process_status_gm', 
            work_process_status_md = '$work_process_status_md', ";
    
    $sql .= $_strSql;
            
    $sql .= "status = 1, 
            comment_user = '$comment_user', 
            date_time_stamp_update = GETDATE() 
            
            WHERE quatation_ID = '$quatation_ID' ";
    //die($sql);
    sqlsrv_query($conn, "SET NAMES UTF8");
    $query1 = sqlsrv_query($conn, $sql);

    $target_dir = "upload/";
    $countfiles = count($_FILES['fileToUpload']['name']);

    $destination = dirname(__FILE__) . '/upload/';
    // $destination = $_SERVER['DOCUMENT_ROOT']  . '\Project_helpdesk\upload\\';

    $array_file_name = array();

    // session_start();
    // $_SESSION['plan'] = "destinationey = " . $destination;
    // header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
    // exit();
    
    //die('file : '.$countfiles);

    if (isset($_FILES["fileToUpload"]["tmp_name"])) {
        for ($key = 0; $key < $countfiles; $key++) {

            $temp = $_FILES["fileToUpload"]["tmp_name"][$key];
            $tempType = explode(".", trim(basename($_FILES["fileToUpload"]["name"][$key])));
            $tempType = str_replace(" ", "_", $tempType);
            $newfilename = current($tempType) . '_' . $num_req . '_' . time() . '.' . end($tempType);

            $type = $_FILES["fileToUpload"]["type"][$key];

            if (empty($temp)) {
                // 20230214 comment by 04404 
                // session_start();
                // $_SESSION['plan'] = "Can't Upload file : " . basename($_FILES["fileToUpload"]["name"][$key]) . "<br> because file is Empty. Please choose another file. ";
                // $_SESSION['plan_status'] = 'error';
                // header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                // exit();
            }else{
               if (file_exists($newfilename)) {
                    session_start();
                    $_SESSION['plan'] = "Can't upload file : " . basename($_FILES['fileToUpload']['name'][$key]) . "<br> because file not exists. Please choose another file.  ";
                    $_SESSION['plan_status'] = 'error';
                    header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                    exit();
                } else { 

                    move_uploaded_file($temp, $destination . $newfilename);   

                    $sql3 = "INSERT INTO quatation_file 
                    (Num_req,File_name,Type,Update_by,Date_create,Date_modified) 
                    VALUES 
                    ('$num_req','$newfilename'  ,'$type', '$name_request' ,GETDATE() ,GETDATE())";
                    sqlsrv_query($conn, "SET NAMES UTF8");

                    $query3 = sqlsrv_query($conn, $sql3);
                    array_push($array_file_name, $newfilename);
                    
                } 
            }            
        }
    } 

    if ($query1) {
        date_default_timezone_set("Asia/Bangkok");
        $adate = date("Y-m-d");
        $ldate = date('Y-m-d', strtotime(' - 1 days'));

        $to = $email_approver_user;
        $subject = 'Alert from System > Request for quotation';
        $fromname = 'ระบบ Request for quotation';
        $message = '<html> 
                <body style="font-family:Tahoma">
                <FONT Size = "3"> 
                
                <H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
                <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 
                   
                    Dear, ' . $approver_nameTH . ' <br> <br> 
                        
                    &nbsp;&nbsp; ขณะนี้ ' . $name_request . ' ได้ดำเนินการ <font color="blue">Revise</font> ข้อมูลใบเสนอราคา <b>เอกสารเลขที่ : ' . $num_req . '</b><br> 

                        
                    &nbsp;&nbsp;    ข้อคิดเห็นของ ผู้ร้องขอ วันเวลา <b> ' . $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_user)) . '</b> <br> <br>

                    
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
                                        <th >วันที่ต้องการ</th>
                                        <th >วันที่ขอ</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <td >Pending (รออนุมัติ)</td>
                                    <td >' . $name_request . '</td>
                                    <td >' . $email . '</td>
                                    <td >' . $department . '</td>
                                    <td >' . $tel . '</td>
                                    <td >' . date('d/m/Y, H:i:s')  . '</td>
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
        $headers = 'From: NoReply.RFQ@ts-engineering.com' . "\r\n" .
            // 'Reply-To: no-reply@ts-engineering.com' . "\r\n" .
            'Content-type: text/html; charset=utf-8' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        // $Send = mail($to, $subject, $message, $headers);
        $Send = fncPhpMailer($to, $subject, $message);

        if ($Send) {
            echo "Email Sending";

            session_start();
            // $_SESSION['plan'] = "Upload file : " . implode("<br>", $array_file_name) . "<br>" . " | Update Successfully and Email Sending! ";
            $_SESSION['plan'] = "Update Successfully and Email Sending! ";
            $_SESSION['plan_status'] = 'success';
            header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
            exit();
            // echo"<script type=\"text/javascript\">alert(\"ไม่สามารถ Update ค่าน้ำมันได้ เมลถูกส่งเรียบร้อยแล้ว!\");</script>";
        } else {
            echo "Email Can Not Send";

            session_start();
            $_SESSION['plan'] = "Update Successfully but Email Can't Send! ";
            header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
            exit();
            //echo"<script type=\"text/javascript\">alert(\"ไม่สามารถ Update ค่าน้ำมัน และส่งเมลไม่ได้!\");</script>";
        }
    } else {
        session_start();
        $_SESSION['plan'] = "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
        $_SESSION['plan_status'] = 'error';
        header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
        exit();


        //save_logfile($con,'ADD','Error ADD Contact Person ['.$sql_add_cp.']',$name);
    }
}

exit;
//*** Reject user not online

sqlsrv_close($conn);
