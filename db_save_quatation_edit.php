<?php
include("connect.php");
require('phpmailer/sendmail.php');
date_default_timezone_set("Asia/Bangkok");

switch ($_POST['submit']) {

    case 'submit':

    if (isset($_POST["quatation_ID"])) {

        $quatation_ID = $_POST["quatation_ID"];
        $comment = $_POST["comment_approver_pu"];

        // กัน '
        if (substr_count($comment, "'") > 0) {
            session_start();
            $_SESSION['plan'] = "Error : ห้ามใส่ '";
            $_SESSION['plan_status'] = 'error';
            exit;
        }

        // ใส่เวลา
        $date_string = date("Y-m-d H:i:s");
        $comment = '@' . $date_string . ' => ' . $comment;

        //  ดึงข้อมูล user
        $sql_find = "SELECT num_req, name_request, email, employee_code_request 
                     FROM quatation 
                     WHERE quatation_ID = '$quatation_ID'";

        $query_find = sqlsrv_query($conn, $sql_find);
        $row = sqlsrv_fetch_array($query_find, SQLSRV_FETCH_ASSOC);

        $num_req = $row["num_req"];
        $name_request = $row["name_request"];
        $email = $row["email"];
        $user_code = $row["employee_code_request"];

        $sql = "UPDATE quatation SET 
                    work_process_status_approvepu = 'success',
                    status = 4,  --  บังคับเป็น 4
                    current_user_code = '$user_code',
                    comment_approver_pu = '$comment',
                    date_time_stamp_approver_pu_last = GETDATE(),
                    date_time_stamp_update = GETDATE()
                WHERE quatation_ID = '$quatation_ID'";

        sqlsrv_query($conn, "SET NAMES UTF8");
        $query = sqlsrv_query($conn, $sql);

        if ($query) {

            //  ส่งเมล
            $to = $email;
            $subject = 'RFQ Completed';
            $message = "
                <h3>RFQ เสร็จสิ้น</h3>
                เลขเอกสาร: $num_req <br>
                ผู้ขอ: $name_request <br><br>
                Comment:<br>
                " . str_replace("@", "<br>", $comment);

            fncPhpMailer($to, $subject, $message);

            session_start();
            $_SESSION['plan'] = "ดำเนินการเสร็จสิ้น";
            header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);

        } else {
            session_start();
            $_SESSION['plan'] = "Update ไม่สำเร็จ";
            $_SESSION['plan_status'] = 'error';
        }
    }

    break;

    case 'cancel':

        if (isset($_POST["quatation_ID"])) {

            $comment = $_POST["comment_approver_pu"];

            if (substr_count($comment, "'") > 0) {
                session_start();
                $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ '";
                $_SESSION['plan_status'] = 'error';
                exit;
            }

            $date_string = date("Y-m-d H:i:s");
            $comment = '@' . $date_string . ' => ' . $comment;

            $quatation_ID = $_POST["quatation_ID"];
            $email = $_POST["email"];

            $sql = "UPDATE quatation SET 
                        work_process_status_approvepu = 'unsuccess',
                        status = 7,
                        comment_approver_pu = '$comment',
                        date_time_stamp_approver_pu_last = GETDATE(),
                        date_time_stamp_update = GETDATE()
                    WHERE quatation_ID = '$quatation_ID'";

            sqlsrv_query($conn, "SET NAMES UTF8");
            $query = sqlsrv_query($conn, $sql);

            if ($query) {

                $to = $email;
                $subject = 'RFQ Rejected';
                $message = "
                    <h3>RFQ ไม่อนุมัติ</h3>
                    Comment:<br>
                    " . str_replace("@", "<br>", $comment);

                fncPhpMailer($to, $subject, $message);

                session_start();
                $_SESSION['plan'] = "Reject สำเร็จ";
                header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);

            } else {
                session_start();
                $_SESSION['plan'] = "Update ไม่สำเร็จ";
                $_SESSION['plan_status'] = 'error';
            }
        }

        break;
}
exit;
sqlsrv_close($conn);
?>