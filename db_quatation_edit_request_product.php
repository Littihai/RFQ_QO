<?php

ini_set('session.gc_maxlifetime', 1000);
error_reporting(~E_NOTICE);
session_start();



if ($_SESSION["Username"] == "") {
    echo "<script type=\"text/javascript\">alert(\"กรุณาเข้าสู่ระบบ\");</script>";
    echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=index.php'>";
    exit();
}

$Username = $_SESSION["Username"];



include("connect.php");

$product = $_POST["product"];
$amount = $_POST["amount"];
$unit = $_POST["unit"];
$price = $_POST["price"];
$request_ID = $_POST["request_ID"];
$quatation_ID = $_POST["quatation_ID"];

if (is_numeric($price) && $price) {
    $sql = "UPDATE request_product SET product = '$product' , amount='$amount',unit = '$unit',price = '$price' WHERE request_ID = '$request_ID' ";
} else {
    // echo "ข้อมูลช่อง 'จำนวน' ต้องเป็นตัวเลข กรุณากรอกใหม่อีกครั้ง";
    $_SESSION['plan'] = "ข้อมูลช่อง 'ราคา' ต้องเป็นข้อมูลตัวเลข กรุณากรอกข้อมูลใหม่อีกครั้ง";
    $_SESSION['plan_status'] = 'error';
    header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
    exit;
}
// $sql = "UPDATE request_product SET product = '$product' , amount=100,unit = '$unit',price = '$price' WHERE request_ID = '$request_ID' ";

sqlsrv_query($conn, "SET NAMES UTF8");
$query1 = sqlsrv_query($conn, $sql);



if ($query1) {
    // $pageBefore = "Location: Quatation_edit.php?quatation_ID=" .  $quatation_ID

    session_start();
    $_SESSION['plan'] = "Update Successfully ! ";

    header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
} else {

    echo "Error: " . $sql1 . "<br>" . sqlsrv_errors($conn);
}


sqlsrv_close($conn);



exit;
