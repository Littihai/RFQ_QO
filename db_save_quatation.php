<?php
// add function by 04726
$basePath = "\\\\172.24.2.1\\wwwroot\\RFQ_QO\\upload";
$num_req = $_POST['num_req'] ?? '';

// เช็คค่า
if ($num_req == '') {
    echo "NO REQ";
    exit;
}

// path folder
$destFolder = $basePath . "\\" . $num_req;

// สร้าง Folder
if (!file_exists($destFolder)) {

    if (mkdir($destFolder, 0777, true)) {
        echo "CREATE SUCCESS: " . $destFolder;
    } else {
        echo "CREATE FAIL";
    }

} else {
    echo "FOLDER EXIST";
}

?>
<?php
include("connect.php");
require('phpmailer/sendmail.php'); //20240808 add by 04404, new function send mail.
error_reporting(~E_NOTICE);
date_default_timezone_set("Asia/Bangkok");

header('Content-Type: text/html; charset=UTF-8');

try {
    if (isset($_POST["num_req"])) {


        echo "<pre>";
        print_r($_POST);
        print_r($_FILES);
        echo "</pre>";


        if (isset($_POST["product"]) && isset($_POST["amount"]) && isset($_POST["unit"])) {
            $product = $_POST["product"];
            $amount = $_POST["amount"];
            $unit = $_POST["unit"];


            if ($product[0] !== '' && $amount[0] !== '' && $unit[0] !== '') {
                $employee_code_request = $_POST['employee_code_request'];
                $name_request = $_POST['name_request'];
                $department = $_POST['department'];
                $tel = $_POST['tel'];
                $email = $_POST['email'];
                $status_name = $_POST["status_name"];

                // $date = $_POST["datepicker"];
                // $time = strtotime($date);
                // $datepicker = date('Ymd', $time);

                $comment_user = $_POST['comment'];
                if (substr_count($comment_user, "'") > 0) {
                    session_start();
                    $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
                    $_SESSION['plan_status'] = 'error';
                    exit;
                }
                $t = time();
                $date_string = date("Y-m-d H:i:s", $t);
                $comment_user = '@' . $date_string . ' => ' . $comment_user;

                $num_req = $_POST['num_req'];
                $approver_user_code = $_POST['approver_user_code'];
                $approver_user_first_level = $_POST['approver_user_first_level'];
                
                // 20220118 add code by 04404
                $rfq_type = $_POST['rfq_type'];
                if ($rfq_type == '') {
                    session_start();
                    $_SESSION['plan'] = "กรุณาเลือกประเภท RFQ.";
                    $_SESSION['plan_status'] = 'error';
                    exit;
                }
                // else{
                //     session_start();
                //     $_SESSION['plan'] = "$rfq_type";
                //     $_SESSION['plan_status'] = 'error';
                //     exit;
                // }
                // 20220118 end add code by 04404

                $month = date('m');
                $year = date('Y');
                $x = "001";
                $fisrt_numRequire = 'REQ';
                $x_str = substr("$x", 0);

                // เช็คข้อมูลอันแรกใน database ว่ามีมั้ย
                $queryLast = "SELECT TOP 1 * FROM quatation ORDER BY quatation_ID DESC";
                $returnedValue = sqlsrv_query($conn, $queryLast);
                $row = sqlsrv_fetch_array($returnedValue, SQLSRV_FETCH_ASSOC);

                if ($row === false) {

                    // die(print_r(sqlsrv_errors(), true));
                    $num_req = $fisrt_numRequire  . $year . "-" . $month . "-" . $x_str;
                } else if ($row === null) {

                    // เช็คข้อมูลอันแรกใน database ว่ามีมั้ย ถ้าไม่มีให้สร้างเป็น REQ2022-09-001
                    // echo "No results were found.\n";
                    $num_req = $fisrt_numRequire  . $year . "-" . $month . "-" . $x_str;
                } else {
                    // เช็คข้อมูลอันแรกใน database ว่ามีมั้ย ถ้ามี
                    do {

                        $mymonth = date_format($row["date_time_stamp"], "m");

                        if ($mymonth == $month) {
                            // ถ้ามี และเป็นเดือนเดียวกัน ให้ + เพิ่มต่อ REQ2022-09-002 
                            $y = substr($row["num_req"], -3, 3);
                            $z =  (int)$y + 1;
                            $x_str = substr("000" . $z, -3, 3);

                            $num_req = $fisrt_numRequire . $year . "-" . $month . "-" . $x_str;
                        } else {
                            // ถ้ามี และเป็นเดือนคนละเดือน ให้สร้างใหม่ เป็น REQ2022-10-001 
                            $y = "000";
                            $z =  (int)$y + 1;
                            $x_str = substr("000" . $z, -3, 3);
                            $num_req = $fisrt_numRequire . $year . "-" . $month . "-" . $x_str;
                        }
                    } while ($row = sqlsrv_fetch_array($returnedValue, SQLSRV_FETCH_ASSOC));
                }



                // เช็คข้อมูล จาก num_req ด้านบน ว่ามีใน database มั้ย
                $queryDup = "SELECT TOP 1 * FROM quatation WHERE num_req = '$num_req' ORDER BY quatation_ID DESC";
                $returnedValueDup = sqlsrv_query($conn, $queryDup);
                $rowDup = sqlsrv_fetch_array($returnedValueDup, SQLSRV_FETCH_ASSOC);

                if ($rowDup === false) {
                    // เช็คข้อมูล จาก num_req แล้วไม่เจอ แล้วให้เท่ากับ num req



                    die(print_r(sqlsrv_errors(), true));
                    $num_req = $num_req;
                } else if ($rowDup === null) {
                    // echo "No results were found.\n";
                    // เช็คข้อมูล จาก num_req แล้วไม่เจอ  แล้วให้เท่ากับ num req
                    $num_req = $num_req;
                } else {
                    do {

                        $mymonth = date_format($rowDup["date_time_stamp"], "m");

                        if ($mymonth == $month) {
                            // เช็คข้อมูล จาก num_req แล้วเจอ และ เดือนคือเดือนเดียวกัน

                            $y = substr($rowDup["num_req"], -3, 3);
                            $z =  (int)$y + 1;
                            $x_str = substr("000" . $z, -3, 3);

                            $num_req = $fisrt_numRequire . $year . "-" . $month . "-" . $x_str;
                        } else {
                            // เช็คข้อมูล จาก num_req แล้วเจอ แล้ว เดือนไม่ใช่เดือนเดียวกัน
                            $y = "000";
                            $z =  (int)$y + 1;
                            $x_str = substr("000" . $z, -3, 3);
                            $num_req = $fisrt_numRequire . $year . "-" . $month . "-" . $x_str;
                        }
                    } while ($rowDup = sqlsrv_fetch_array($returnedValueDup, SQLSRV_FETCH_ASSOC));
                }
                
                if ($approver_user_code == '') {

                    // echo "กรุณาเลือกผู้อนุมัติ";
                    session_start();
                    $_SESSION['plan'] = "กรุณาเลือกผู้อนุมัติ";
                    $_SESSION['plan_status'] = 'error';
                    // header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                    exit;
                } else {
                    
                     $pu_code = $_POST['pu_code'] ?? '';
                    if ($pu_code == '') {
                        session_start();
                        $_SESSION['plan'] = "กรุณาเลือก Buyer";
                        $_SESSION['plan_status'] = 'error';
                        exit;
                    }

                    // หา Fullname จาก EmployeeCode 04726
                    list($CateID, $pu_code) = explode('|', $_POST['pu_code']);
                    $sqlPU = "SELECT ThFullName 
                        FROM vw_Employee 
                        WHERE EmployeeCode = '$pu_code'
                    ";

                    $queryPU = sqlsrv_query($conn, $sqlPU);

                    if (!$queryPU) {
                        echo "<pre>";
                        print_r(sqlsrv_errors());
                        echo "</pre>";
                        exit;
                    }

                    $rowPU = sqlsrv_fetch_array($queryPU, SQLSRV_FETCH_ASSOC);

                    if (!$rowPU) {
                        session_start();
                        $_SESSION['plan'] = "ไม่พบข้อมูลพนักงาน";
                        $_SESSION['plan_status'] = 'error';
                        exit;
                    }
                    $pu_nameTH = $rowPU['ThFullName'];
                    
                    // $sqlT = "SELECT * FROM vw_Employee where EmployeeCode = '$approver_user_code'"; //Comment by 04726
                    $sqlT = "SELECT * FROM vw_Employee where EmployeeCode = '$pu_code'";
                    $queryT = sqlsrv_query($conn, $sqlT);
                    $resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC);
                    $approver_user_nameTH = $resultT['ThFullName'];
                    $email_approver_user = $resultT['Email']; //Comment by 04726
                    $email_bcc = 'littichai_y@ts-engineering.com'; //add email by 04726 20260508
                    $email_cc = 'nantanee_t@ts-engineering.com,navaporn_i@ts-engineering.com'; //add email by 04726 20260508
                    // nantanee_t@ts-engineering.com,navaporn_i@ts-engineering.com
                    // $email_bcc = 'system-admin@ts-engineering.com'; //add email by 04726 20260508
                    // $email_cc = 'kriangsak_k@ts-engineering.com'; //add email by 04726 20260508

                    for ($count = 0; $count < count($product); $count++) {
                        $product_keep = $product[$count];
                        $amount_keep = $amount[$count];
                        $unit_keep = $unit[$count];



                        if (is_numeric($amount_keep) && $amount_keep) {

                            // echo "<script>console.log('" . $product_keep . "')</script>";
                        } else {
                            // echo "ข้อมูลช่อง 'จำนวน' ต้องเป็นตัวเลข กรุณากรอกใหม่อีกครั้ง";
                            session_start();
                            $_SESSION['plan'] = "ข้อมูลช่อง 'จำนวน' ต้องเป็นข้อมูลตัวเลข กรุณากรอกข้อมูลใหม่อีกครั้ง";
                            $_SESSION['plan_status'] = 'error';
                            // header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                            exit;
                        }

                        if (substr_count($product_keep, "'") > 0) {
                            session_start();
                            $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
                            $_SESSION['plan_status'] = 'error';
                            exit;
                        }
                    }


                    $queryCheck = "SELECT TOP 1 * FROM quatation WHERE num_req = '$num_req' ORDER BY quatation_ID DESC";
                    $returnedValueCheck = sqlsrv_query($conn, $queryCheck);
                    $rowCheck = sqlsrv_fetch_array($returnedValueCheck, SQLSRV_FETCH_ASSOC);

                    if ($rowCheck === false) {
                        for ($count1 = 0; $count1 < count($product); $count1++) {
                            $product_keep = $product[$count1];
                            $amount_keep = $amount[$count1];
                            $unit_keep = $unit[$count1];

                            $sql2 = "INSERT INTO request_product 
                                    (product,amount,unit,num_req) 
                                    VALUES 
                                    ('$product_keep','$amount_keep','$unit_keep','$num_req')";
                            sqlsrv_query($conn, "SET NAMES UTF8");
                            $query2 = sqlsrv_query($conn, $sql2);
                        }
                    } else if ($rowCheck === null) {
                        for ($count1 = 0; $count1 < count($product); $count1++) {
                            $product_keep = $product[$count1];
                            $amount_keep = $amount[$count1];
                            $unit_keep = $unit[$count1];

                            $sql2 = "INSERT INTO request_product 
                                    (product,amount,unit,num_req) 
                                    VALUES 
                                    ('$product_keep','$amount_keep','$unit_keep','$num_req')";
                            sqlsrv_query($conn, "SET NAMES UTF8");
                            $query2 = sqlsrv_query($conn, $sql2);
                        }
                    } else {
                        $mymonth = date_format($rowDup["date_time_stamp"], "m");

                        if ($mymonth == $month) {
                            // เช็คข้อมูล จาก num_req แล้วเจอ และ เดือนคือเดือนเดียวกัน

                            $y = substr($rowDup["num_req"], -3, 3);
                            $z =  (int)$y + 1;
                            $x_str = substr("000" . $z, -3, 3);

                            $num_req = $fisrt_numRequire . $year . "-" . $month . "-" . $x_str;
                        } else {
                            // เช็คข้อมูล จาก num_req แล้วเจอ แล้ว เดือนไม่ใช่เดือนเดียวกัน
                            $y = "000";
                            $z =  (int)$y + 1;
                            $x_str = substr("000" . $z, -3, 3);
                            $num_req = $fisrt_numRequire . $year . "-" . $month . "-" . $x_str;
                        }


                        for ($count1 = 0; $count1 < count($product); $count1++) {
                            $product_keep = $product[$count1];
                            $amount_keep = $amount[$count1];
                            $unit_keep = $unit[$count1];

                            $sql2 = "INSERT INTO request_product 
                                    (product,amount,unit,num_req) 
                                    VALUES 
                                    ('$product_keep','$amount_keep','$unit_keep','$num_req')";
                            sqlsrv_query($conn, "SET NAMES UTF8");
                            $query2 = sqlsrv_query($conn, $sql2);
                        }
                    }

                    $target_dir = "upload/";
                    $destination = dirname(__FILE__) . '/upload/';
                    $countfiles = count($_FILES['files']['name']);
                    if (isset($_FILES["files"]["tmp_name"])) {
                        for ($key = 1; $key < $countfiles; $key++) {

                            // foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {

                            $temp = $_FILES["files"]["tmp_name"][$key];
                            $tempType = explode(".", $_FILES["files"]["name"][$key]);
                            $tempType = str_replace(" ", "_", $tempType);
                            $newfilename = current($tempType) . '_' . $num_req . '_' . time() . '.' . end($tempType);

                            // $name = $_FILES["files"]["name"][$key] .  '_' . $num_req;
                            $type = $_FILES["files"]["type"][$key];
                            if (empty($temp)) {
                                session_start();
                                $_SESSION['plan'] = "Can't Upload file : " . basename($_FILES["fileToUpload"]["name"][$key]) . "<br> because file is Empty. Please choose another file. ";
                                $_SESSION['plan_status'] = 'error';
                                // header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                                exit;
                            }

                            if (file_exists($newfilename)) {
                                session_start();
                                $_SESSION['plan'] = "Can't upload file : " . basename($_FILES['fileToUpload']['name'][$key]) . "<br> because file not exists. Please choose another file.  ";
                                $_SESSION['plan_status'] = 'error';
                                // header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                                exit;
                            } else {
                                // move_uploaded_file($temp, $target_dir . $newfilename);
                                move_uploaded_file($temp, $destination . $newfilename);
                                // move_uploaded_file($temp, $target_dir . $newfilename);
                                $sql2 = "INSERT INTO quatation_file 
                                    (Num_req,File_name,Type,Update_by,Date_create,Date_modified) 
                                    VALUES 
                                    ('$num_req','$newfilename'  ,'$type', '$name_request' ,GETDATE() ,GETDATE())";
                                //  ('" . $num_req ."','".$newfilename."'  ,'".$type."', '".$name_request."' ,".GETDATE() .",".GETDATE().") ";
                                sqlsrv_query($conn, "SET NAMES UTF8");
                                $query2 = sqlsrv_query($conn, $sql2);
                            }
                        }
                    }
                    
                    $app_lev_dm = "skip";
                    $app_lev_gm = "skip";
                    $app_lev_md = "skip";

                    $work_process_status_approvepu = "skip"; // ข้าม Purchase Manager 04726
                    $work_process_status_pu = "pending";     // ไปหา Staff เลย 04726
                    
                    $sqlAllLev = "SELECT CAST(STUFF((SELECT ', ' +  CAST(doc.Lv AS NVARCHAR(4000)) FROM 
                    (SELECT lev.Lv
                        FROM vw_Employee AS emp LEFT OUTER JOIN ApproveLevelVw AS lev ON emp.PlantCode = lev.Plant AND emp.DepartmentCode = lev.Dept
                        WHERE (emp.EmployeeCode = '$employee_code_request')
                    ) AS Doc 
                    GROUP BY Doc.Lv
                    FOR XML PATH('')), 1, 2, N'') AS nvarchar(4000)) As 'all_lev' ";
                    $queryAllLev = sqlsrv_query($conn, $sqlAllLev);
                    $resultAllLev = sqlsrv_fetch_array($queryAllLev, SQLSRV_FETCH_ASSOC);
                    if (!$resultAllLev) {
                    } else if ($resultAllLev === null) {
                    } else {
                        // session_start();
                        // $_SESSION['plan'] = $resultAllLev['all_lev'];
                        // $_SESSION['plan_status'] = 'error';
                        // exit;
                        if($rfq_type == '1'){
                            if (strpos($resultAllLev['all_lev'], "3") !== false){
                                $app_lev_md = "wait";
                            }
                            if (strpos($resultAllLev['all_lev'], "2") !== false){
                                $app_lev_gm = "wait";
                            }
                            if (strpos($resultAllLev['all_lev'], "1") !== false){
                                $app_lev_dm = "wait";
                            }
                        }else if($rfq_type == '3' || $rfq_type == '4'){
                            if (strpos($resultAllLev['all_lev'], "1") !== false){
                                $app_lev_dm = "wait";
                            }else if (strpos($resultAllLev['all_lev'], "2") !== false){
                                $app_lev_gm = "wait";
                            }else if (strpos($resultAllLev['all_lev'], "3") !== false){
                                $app_lev_md = "wait";
                            }
                        }else{
                            if (strpos($resultAllLev['all_lev'], "1") !== false || strpos($resultAllLev['all_lev'], "2") !== false){
                                if (strpos($resultAllLev['all_lev'], "2") !== false){
                                    $app_lev_gm = "wait";
                                }
                                if (strpos($resultAllLev['all_lev'], "1") !== false){
                                    $app_lev_dm = "wait";
                                }
                            }else{
                                if (strpos($resultAllLev['all_lev'], "3") !== false){
                                    $app_lev_md = "wait";
                                }
                            }
                        }
                    }
                    
                    $sql = "INSERT INTO quatation (employee_code_request,name_request,department,tel,email,date_time_stamp,comment_user,num_req,";

                    if($approver_user_first_level == 3){
                        $sql .= "approver_md_code,approver_md_nameTH,";
                        $app_lev_md = "pending";
                    }else if($approver_user_first_level == 2){
                        $sql .= "approver_gm_code,approver_gm_nameTH,";
                        $app_lev_gm = "pending";
                    }else{
                        $sql .= "approver_user_code,approver_user_nameTH,";
                        $app_lev_dm = "pending";
                    }

                    // session_start();
                    // $_SESSION['plan'] = $app_lev_dm.", ".$app_lev_gm.", ".$app_lev_md; //wait, wait, pending
                    // $_SESSION['plan_status'] = 'error';
                    // exit;
                    
                    // $pu_nameTH = "นางสาวมาริสา ดวงแก้ว";
                    // $pu_code = '04333';
                    //Add Function by 04726 20260331
                  
                    $sql .= "status,work_process_status_user,work_process_status_approvepu,work_process_status_pu,date_time_stamp_update, tse_rfq_type,work_process_status_gm,work_process_status_md,pu_code,pu_nameTH) 
                            VALUES ('$employee_code_request','$name_request','$department',
                            '$tel','$email',GETDATE(),'$comment_user','$num_req',
                            '$approver_user_code','$approver_user_nameTH','3','$app_lev_dm','wait','wait',GETDATE(), '$rfq_type','$app_lev_gm','$app_lev_md','$pu_code','$pu_nameTH')";
                   
                  // =============================================================================
                    if (!isset($_POST['pu_code'])) {
                        die("กรุณาเลือก Category");
                    }

                    if (strpos($_POST['pu_code'], '|') !== false) {
                        list($CateID, $pu_code) = explode('|', $_POST['pu_code']);
                    } else {
                        die("รูปแบบข้อมูลผิด");
                    }

                    $num_req = $_POST['num_req']; // RFQ Number
                    $employee_code_request = $_POST['employee_code_request'];
                    $sql_lt = "SELECT LeadTime 
                            FROM Vw_CateMaster 
                            WHERE CateID = '$CateID'";

                    $query_lt = sqlsrv_query($conn, $sql_lt);

                    if (!$query_lt) {
                        print_r(sqlsrv_errors());
                        exit;
                    }

                    $row_lt = sqlsrv_fetch_array($query_lt, SQLSRV_FETCH_ASSOC);

                    if (!$row_lt) {
                        die("ไม่พบ LeadTime");
                    }

                    $LeadTime = (int)$row_lt['LeadTime'];

                    $sql_rfq = "INSERT INTO TSE_CateLeadTime (RFQNum,CateID,BuyerNum,LeadTime,RowPointer,UpdateBy,UpdateDate,CreatedBy,CreatedDate)
                    VALUES ('$num_req','$CateID','$pu_code','$LeadTime',NEWID(),'$employee_code_request',GETDATE(),'$employee_code_request',GETDATE())";
                    $stmt = sqlsrv_query($conn, $sql_rfq);
                // =============================================================================
                                        // session_start();
                    // $_SESSION['plan'] = "$sql";
                    // $_SESSION['plan_status'] = 'error';
                    // exit;

                    sqlsrv_query($conn, "SET NAMES UTF8");
                    $query = sqlsrv_query($conn, $sql);

                    if ($query) {

                        $sqlA = "SELECT * FROM quatation WHERE num_req = '$num_req' ";
                        $queryA = sqlsrv_query($conn, $sqlA);
                        $resultA = sqlsrv_fetch_array($queryA, SQLSRV_FETCH_ASSOC);
                        $quatation_ID = $resultA["quatation_ID"];

                        date_default_timezone_set("Asia/Bangkok");
                        $adate = date("Y-m-d");
                        $ldate = date('Y-m-d', strtotime(' - 1 days'));

                        $to = $email_approver_user;
                        // $cc = $email_cc; // add email by 04726 20260508
                        $bcc = $email_bcc; // add email by 04726 20260508
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

                        // $Send = mail($to, $subject, $message, $headers);
                        $Send = fncPhpMailer($to, $cc, $bcc, $subject, $message);

                        if ($Send) {
                            echo "Email Sending";

                            session_start();
                            $_SESSION['plan'] = "Update Successfully and Email Sending! ";
                            $_SESSION['plan_status'] = 'success';
                            // header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                            exit;
                        } else {
                            echo "Email Can Not Send";

                            session_start();
                            $_SESSION['plan'] = "Update Successfully but Email Can't Send! ";
                            $_SESSION['plan_status'] = 'error';
                            // header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                            exit;
                        }
                    } else {

                        // echo "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
                        session_start();
                        $_SESSION['plan'] = "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
                        $_SESSION['plan_status'] = 'error';
                        // header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                        exit;
                        //save_logfile($con,'ADD','Error ADD Contact Person ['.$sql_add_cp.']',$name);
                    }
                }
            } else {

                // echo 'กรุณาเพิ่ม รายการสินค้า , จำนวน ,หน่วยนับ ให้ครบถ้วน';
                session_start();
                $_SESSION['plan'] = "กรุณาเพิ่ม รายการสินค้า, จำนวน, หน่วยนับ ให้ครบถ้วน";
                $_SESSION['plan_status'] = 'error';
                // header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
                exit;
            }
        } else {

            // echo 'กรุณาเพิ่ม รายการสินค้า , จำนวน ,หน่วยนับ ให้ครบถ้วน';
            session_start();
            $_SESSION['plan'] = "กรุณาเพิ่ม รายการสินค้า, จำนวน, หน่วยนับ ให้ครบถ้วน";
            $_SESSION['plan_status'] = 'error';
            // header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
            exit;
        }
    }



    exit;
} catch (Exception $e) {

    session_start();
    $_SESSION['plan'] = "Caught exception : <b>" . $e->getMessage() . "</b><br/>";
    $_SESSION['plan_status'] = 'error';
}

//*** Reject user not online

sqlsrv_close($conn);