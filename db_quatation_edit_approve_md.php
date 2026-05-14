<?php
include("connect.php");
require('phpmailer/sendmail.php'); //20240808 add by 04404, new function send mail.
error_reporting(~E_NOTICE);
date_default_timezone_set("Asia/Bangkok");
switch ($_POST['submit']) {
  case 'submit':
    if (isset($_POST["quatation_ID"])) {
      $quatation_ID = $_POST["quatation_ID"];
      if ($_POST["approver_code"] != '') {
        $comment_approver_user = $_POST["comment_approver_user"];
        if (substr_count($comment_approver_user, "'") > 0) {
          session_start();
          $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
          $_SESSION['plan_status'] = 'error';
          exit;
        }
        $comment_approver_user_last = $_POST["comment_approver_user_last"];

        $t = time();
        $date_string = date("Y-m-d H:i:s", $t);
        $comment_approver_user = $comment_approver_user_last . '@' . $date_string . ' => ' . $comment_approver_user;

        $two = str_replace("@", "&#13;&#10;@", $comment_approver_user);
        echo '$two = ' . $two . '<br>';
        $pos = strpos($two, "&#13;&#10;@");
        echo '$pos = ' . $pos . '<br>';
        if ($pos !== false) {
          $comment_approver_user = substr_replace($two, '@', $pos, strlen("&#13;&#10;@"));
          // echo $newstring.'<br>';
        }

        $num_req = $_POST["num_req"];
        $name_request   = $_POST["name_request"];
        $email = $_POST["email"];
        $department = $_POST["department"];
        $tel = $_POST["tel"];
        // $date_picker = $_POST["date_picker"];

        $sqlQuatation = "SELECT * FROM quatation WHERE quatation_ID = '$quatation_ID' ";
        $queryQuatation = sqlsrv_query($conn, $sqlQuatation);
        $resultQuatation = sqlsrv_fetch_array($queryQuatation, SQLSRV_FETCH_ASSOC);
        $approver_user_nameTH = $resultQuatation["approver_user_nameTH"];
        $date_time_stamp = $resultQuatation["date_time_stamp"];
        $employee_code_request = $resultQuatation["employee_code_request"];

        // $approver_pu_code = $_POST["approver_pu_code"];
        $approver_code = $_POST["approver_code"];

        $sqlApprover = "SELECT * FROM vw_Employee where EmployeeCode = '$approver_code' ";
        $queryApprover = sqlsrv_query($conn, $sqlApprover);
        $resultApprover = sqlsrv_fetch_array($queryApprover, SQLSRV_FETCH_ASSOC);
        $approver_nameTH = $resultApprover["ThFullName"];
        $email_approver = $resultApprover["Email"];
        
        $status_dm = $resultQuatation["work_process_status_user"];
        $status_gm = $resultQuatation["work_process_status_gm"];
        $status_md = $resultQuatation["work_process_status_md"];
        echo '$status_dm = ' . $status_dm . '<br>';
        echo '$status_gm = ' . $status_gm . '<br>';
        echo '$status_md = ' . $status_md . '<br>';

        $sql = "UPDATE quatation SET 
                work_process_status_md = 'success',
                work_process_status_approvepu = 'pending',
                status = 10, 
                approver_pu_code='$approver_code',
                approver_pu_nameTH='$approver_nameTH',
                comment_approver_md = '$comment_approver_user' , 
                date_time_stamp_approver_md = GETDATE(),
                date_time_stamp_update = GETDATE() 
        WHERE quatation_ID = '$quatation_ID'";

//die($sql); //---------------------------------------------------
        sqlsrv_query($conn, "SET NAMES UTF8");
        $query1 = sqlsrv_query($conn, $sql);

        if ($query1) {

          date_default_timezone_set("Asia/Bangkok");
          $adate = date("Y-m-d");
          $ldate = date('Y-m-d', strtotime(' - 1 days'));

          $to = $email_approver;
          $subject = 'Alert from System > Request for quotation';
          $fromname = 'ระบบ Request for quotation';
          $message = '<html> 
                                    <body style="font-family:Tahoma">
                                    <FONT Size = "3"> 
                                    
                                    
                                        <H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
                                        <H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 

                                        <b> Dear, ' . $approver_nameTH . '</b>  <br> <br> 
                                            
                                        &nbsp;&nbsp; รอการ approve จากท่าน เนื่องจากทาง ' . $approver_user_nameTH . ' ได้ทำการ <font color="blue">Approve </font> <b>เอกสารเลขที่ : ' . $num_req . '</b><br> 


                                        &nbsp;&nbsp;    ข้อคิดเห็นของ ผู้อนุมัติ วันเวลา <b> ' . $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_approver_user)) . '</b> <br> <br>

                                          
                                        
                                        &nbsp;&nbsp; <table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
                                        <tbody>
                                        <tr>
                                        <td style="border: 2px solid #ccc; border-collapse: collapse;">
                                        <table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
                                                    <thead>                                        
                                                        <tr>
                                                            <th >Status</th>
                                                            <th width="20%">ผู้ร้องขอ</th>
                                                            <th >อีเมล์</th>
                                                            <th >แผนก</th>
                                                            <th >เบอร์ติดต่อ</th>
                                                            <th >วันที่ขอ</th>
                                                            <th >วันที่อัพเดท</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <td >Approved by Managing Director (Managing Director อนุมัติแล้ว)</td>
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
            // 'Reply-To: no-reply@thaisummit.co.th' . "\r\n" .
            'Content-type: text/html; charset=utf-8' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

          // $Send = mail($to, $subject, $message, $headers);
          $Send = fncPhpMailer($to, $subject, $message);

          if ($Send) {
            echo "Email Sending";

            session_start();
            $_SESSION['plan'] = "Update Successfully and Email Sending!";
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
      } else {
        session_start();
        $_SESSION['plan'] = "กรุณาเลือกผู้จัดการแผนกจัดซื้อ ..";
        $_SESSION['plan_status'] = 'error';

        header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
      }
    }
    break;

  case 'cancel':
    $comment_approver_user = $_POST["comment_approver_user"];
    if (substr_count($comment_approver_user, "'") > 0) {
      session_start();
      $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
      $_SESSION['plan_status'] = 'error';
      exit;
    }
    $comment_approver_user_last = $_POST["comment_approver_user_last"];
    $t = time();
    $date_string = date("Y-m-d H:i:s", $t);

    $comment_approver_user = $comment_approver_user_last . '@' . $date_string . ' => ' . $comment_approver_user;

    $two = str_replace("@", "&#13;&#10;@", $comment_approver_user);
    echo '$two = ' . $two . '<br>';
    $pos = strpos($two, "&#13;&#10;@");
    echo '$pos = ' . $pos . '<br>';
    if ($pos !== false) {
      $comment_approver_user = substr_replace($two, '@', $pos, strlen("&#13;&#10;@"));
      // echo $newstring.'<br>';
    }

    $quatation_ID = $_POST["quatation_ID"];
    $num_req = $_POST["num_req"];
    // $name_request = $_POST["name_request"];
    $email = $_POST["email"];
    $department = $_POST["department"];
    $tel = $_POST["tel"];
    // $date_picker = $_POST["date_picker"];

    $sqlQuatation = "SELECT * FROM quatation WHERE quatation_ID = '$quatation_ID' ";
    $queryQuatation = sqlsrv_query($conn, $sqlQuatation);
    $resultQuatation = sqlsrv_fetch_array($queryQuatation, SQLSRV_FETCH_ASSOC);
    $approver_md_nameTH = $resultQuatation["approver_md_nameTH"];
    $date_time_stamp = $resultQuatation["date_time_stamp"];
    $name_request =  $resultQuatation["name_request"];

    $sql = "UPDATE quatation 
            SET work_process_status_md = 'unsuccess', 
            status = 12 , 
            comment_approver_md = '$comment_approver_user' , 
            date_time_stamp_approver_md = GETDATE(), 
            date_time_stamp_update = GETDATE()  
            WHERE quatation_ID = '$quatation_ID'";
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
                                   
                                    <b>Dear, ' . $name_request . '</b> <br> <br>
                                        
                                    &nbsp;&nbsp; เนื่องจาก ' . $approver_md_nameTH . ' ได้ทำการกด <font color="red">ไม่อนุมัติ</font> <b>เอกสารเลขที่ : ' . $num_req . '</b><br> 

                    
                                        
                                    &nbsp;&nbsp;    ข้อคิดเห็นของ ผู้อนุมัติ วันเวลา <b> ' . $str_replace = str_replace("@", "<br>", str_replace("=>", "ความคิดเห็น : ", $comment_approver_user)) . '</b> <br> <br>

                                      
                                    
                                    &nbsp;&nbsp; <table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
                                    <tbody>
                                    <tr>
                                    <td style="border: 2px solid #ccc; border-collapse: collapse;">
                                    <table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
                                                <thead>                                        
                                                    <tr>
                                                        <th>Status</th>
                                                        <th width="20%">ผู้ร้องขอ</th>
                                                        <th >อีเมล์</th>
                                                        <th >แผนก</th>
                                                        <th >เบอร์ติดต่อ</th>
                                                   
                                                        <th >วันที่ขอ</th>
                                                        <th >วันที่อัพเดท</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td >Disapproval by Managing Director (ให้ User Revise ใหม่)</td>
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
        $_SESSION['plan'] = "Update Successfully and Email Sending!";
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