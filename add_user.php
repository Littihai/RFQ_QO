<?php
include('connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$params = array(
    'pu_code' => $_POST['pu_code'],
    'Type' => $_POST['addType'],
);

$pu_code = $_POST['pu_code'];
$Type = $_POST['addType'];


$sqlpu = "SELECT * FROM vw_Employee where EmployeeCode = '$pu_code' ";
$querypu = sqlsrv_query($conn, $sqlpu);
$resultpu = sqlsrv_fetch_array($querypu, SQLSRV_FETCH_ASSOC);
$EmployeeCode = $resultpu['EmployeeCode'];
$ThFullName = $resultpu['ThFullName'];
$PlantNameTH = $resultpu['PlantNameTH'];
$SubLevelNo = $resultpu['SubLevelNo'];
$Position = $resultpu['Position'];



$sqlCheck = "SELECT * FROM admin_purchase where EmployeeCode = '$pu_code' and Type = '$Type' ";
// $queryCheck = sqlsrv_query($conn, $sqlCheck);
// $resultCheck = sqlsrv_fetch_array($queryCheck, SQLSRV_FETCH_ASSOC);

$queryCheck = sqlsrv_query($conn, $sqlCheck, array(), array("Scrollable" => 'static'));
$totalCheck = sqlsrv_num_rows($queryCheck);



if ($totalCheck == 0) {

    $sql = "INSERT INTO admin_purchase (EmployeeCode,ThFullName,PlantNameTH,SubLevelNo,Position,Type) 
    values ('$EmployeeCode','$ThFullName','$PlantNameTH','$SubLevelNo','$Position','$Type')";


    $stmt = $conn2->prepare($sql);
    $stmt->execute();
    // $stmt->execute($params);

    // $sql1 = "SELECT * FROM BOM WHERE BOM_id = (SELECT MAX(BOM_id) FROM BOM)";
    $sql1 = "SELECT * FROM admin_purchase ORDER BY Admin_purchaseID DESC OFFSET 0 ROWS FETCH FIRST 1 ROW ONLY ";

    $stmt1 = $conn2->prepare($sql1);
    $stmt1->execute();
    $results = $stmt1->fetchAll(PDO::FETCH_ASSOC);


    // if ($query == true) {
    if ($results) {

        $data = array(
            'status' => 'success',
            'results' => $results,
        );

        echo json_encode($data);
    } else {
        $data = array(
            'status' => 'Error : ' . $results,

        );
        echo json_encode($data);
    }
} else {

    $data = array(
        'status' => 'Error : Data is already exists.',

    );
    echo json_encode($data);
}


// $params = array(
//     'pu_code' => $_POST['pu_code'],

// );
