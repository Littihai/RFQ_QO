<?php
include('connect.php');

$id = $_POST['id'];


$sqlJa = "SELECT * FROM admin_purchase where Admin_purchaseID = '$id' ";
$queryJa = sqlsrv_query($conn, $sqlJa);
$resultJa = sqlsrv_fetch_array($queryJa, SQLSRV_FETCH_ASSOC);
$EmployeeCode = $resultJa['EmployeeCode'];


if ($EmployeeCode != '1100528') {


    $sql = "DELETE FROM admin_purchase WHERE Admin_purchaseID='$id'";
    $stmt = $conn2->prepare($sql);
    $stmt->execute();



    if ($stmt) {
        $data = array(
            'status' => 'success',
        );

        echo json_encode($data);
    } else {
        $data = array(
            'status' => 'failed , ',

        );

        echo json_encode($data);
    }
} else {

    $data = array(
        'status' => 'Error : ไม่สามารถลบ Super Admin ได้ กรุณาติดต่อ Programmer',
    );

    echo json_encode($data);
}
