<?php
include("connect.php");
date_default_timezone_set("Asia/Bangkok");
if (isset($_POST["num_req"])) {


    // echo "<pre>";
    // print_r($_POST);
    // print_r($_FILES);
    // echo "</pre>";


    if (isset($_POST["product"]) && isset($_POST["amount"]) && isset($_POST["unit"]) && isset($_POST["price"])) {
        $product = $_POST["product"];
        $amount = $_POST["amount"];
        $unit = $_POST["unit"];
        $price = $_POST["price"];

        if ($product[0] !== '' && $amount[0] !== '' && $unit[0] !== '' && $price[0] !== '') {
            $employee_code_request = $_POST['employee_code_request'];
            $name_request = $_POST['name_request'];
            $department = $_POST['department'];
            $tel = $_POST['tel'];
            $email = $_POST['email'];
            $date_picker = $_POST['date_picker'];
            $comment = $_POST['comment'];
            if (substr_count($comment_user, "'") > 0) {
                session_start();
                $_SESSION['plan'] = "Error : ห้ามใส่อักขระพิเศษ ' กรุณากรอกข้อมูลอีกครั้ง";
                $_SESSION['plan_status'] = 'error';
                exit;
            }
            $t = time();
            $date_string = date("Y-m-d H:i:s", $t);
            $comment = '@' . $date_string . ' => ' . $comment;

            $num_req = $_POST['num_req'];
            $approver_user_code = $_POST['approver_user_code'];

            echo $approver_user_code;


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


            for ($count = 0; $count < count($product); $count++) {
                $product_keep = $product[$count];
                $amount_keep = $amount[$count];
                $unit_keep = $unit[$count];
                $price_keep = $price[$count];
                // if ($product_keep != '' && $amount_keep != '' && $unit_keep != '' && $price_keep != '') {
                $sql1 = "INSERT INTO request_product (product,amount,unit,price,num_req) 
                VALUES ('$product_keep','$amount_keep','$unit_keep','$price_keep','$num_req'))";


                sqlsrv_query($conn, "SET NAMES UTF8");

                $query1 = sqlsrv_query($conn, $sql1);
            }




            $sql = "INSERT INTO quatation (employee_code_request,name_request,department,tel,email,date_time_stamp,date_picker,comment,num_req,approver_user_code,date_time_stamp_update) VALUES ('$employee_code_request','$name_request','$department','$tel','$email',GETDATE(),'$date_picker','$comment','$num_req','$approver_user_code',GETDATE())";
            sqlsrv_query($conn, "SET NAMES UTF8");
            $query = sqlsrv_query($conn, $sql);


            if ($query) {

                echo '  Data have been add Succesfully.';


                // echo "<script type='text/javascript'>";
                // 
                // echo "</script>";
            } else {

                session_start();
                $_SESSION['plan'] = "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
                $_SESSION['plan_status'] = 'error';
                exit;
                echo "Error: " . $sql . "<br>" . sqlsrv_errors($conn);


                //save_logfile($con,'ADD','Error ADD Contact Person ['.$sql_add_cp.']',$name);
            }
        } else {

            echo 'กรุณาเพิ่ม รายการสินค้า , จำนวน ,หน่วยนับ ,ราคา ให้ครบถ้วน';
            session_start();
            $_SESSION['plan'] = "กรุณาเพิ่ม รายการสินค้า , จำนวน ,หน่วยนับ ให้ครบถ้วน";
            $_SESSION['plan_status'] = 'error';
            exit;
        }
    } else {
        session_start();
        $_SESSION['plan'] = "กรุณาเพิ่ม รายการสินค้า , จำนวน ,หน่วยนับ ให้ครบถ้วน";
        $_SESSION['plan_status'] = 'error';
        exit;
        echo 'กรุณาเพิ่ม รายการสินค้า , จำนวน ,หน่วยนับ ,ราคา ให้ครบถ้วน';
    }
}



exit;
//*** Reject user not online

sqlsrv_close($conn);
