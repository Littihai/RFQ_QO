<?php
include('connect.php');

$params = array(

    'employeeID' => $_POST['employeeID'],

);


$sql1 = "SELECT * FROM vw_Employee where EmployeeCode = :employeeID ";

$stmt1 = $conn2->prepare($sql1);
$stmt1->execute($params);
$results = $stmt1->fetchAll(PDO::FETCH_ASSOC);


if ($results) {

    $data = array(
        'status' => 'success',
        'results' => $results,

    );

    echo json_encode($data);
} else {

    $data = array(
        'status' => 'false',

    );

    echo json_encode($data);
}


    
// if ($results) {

//     $data = array(
//         'status' => 'success',
//     );


//     echo json_encode($data);
// } else {
//     $data = array(
//         'status' => 'false',
//     );

//     echo json_encode($data);
// }
