<?php include('connect.php');

$output = array();
$sql = "SELECT * FROM admin_purchase";

$requestData = $_REQUEST;

// $totalQuery = sqlsrv_query($conn, $sql);
$totalQuery = sqlsrv_query($conn, $sql, array(), array("Scrollable" => 'static'));
$totalData = sqlsrv_num_rows($totalQuery);
$total_all_rows = $totalData;
// $totalQuery = mysqli_query($con,$sql);
// $total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
    0 => 'Admin_purchaseID',
    1 => 'EmployeeCode',
    2 => 'ThFullName',
    3 => 'PlantNameTH',
    4 => 'SubLevelNo',
    5 => 'Position',

);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE Admin_purchaseID like '%" . $search_value . "%'";
    $sql .= " OR EmployeeCode like '%" . $search_value . "%'";
    $sql .= " OR ThFullName like '%" . $search_value . "%'";
    $sql .= " OR PlantNameTH like '%" . $search_value . "%'";
    $sql .= " OR SubLevelNo like '%" . $search_value . "%'";
    $sql .= " OR Position like '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $columns[$column_name] . " " . $order . "";
} else {
    $sql .= " ORDER BY id desc";
}

if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT  " . $start . ", " . $length;
}


// $query = sqlsrv_query($conn, $sql);

$sql2 = "SELECT * FROM admin_purchase ORDER BY EmployeeCode";
$query2 = sqlsrv_query($conn, $sql2, array(), array("Scrollable" => 'static'));

// $result = sqlsrv_fetch_array($query2, SQLSRV_FETCH_ASSOC);
$count_rows = sqlsrv_num_rows($query2);

// $query2 = mysqli_query($con, $sql);
// $count_rows = mysqli_num_rows($query2);
$data = array();

while ($row = sqlsrv_fetch_array($query2, SQLSRV_FETCH_ASSOC)) {



    $sub_array = array();
    $sub_array[] = $row['Admin_purchaseID'];
    $sub_array[] = '<a href="javascript:void();" data-id="' . $row['Admin_purchaseID'] . '"  class="btn btn-danger btn-sm btn-block deleteBtn " ><i class="fa fa-times"></i> Delete</a>';
    $sub_array[] = $row['EmployeeCode'];
    $sub_array[] = $row['ThFullName'];
    $sub_array[] = $row['PlantNameTH'];
    $sub_array[] = $row['SubLevelNo'];
    // $sub_array[] = $row['Type'];
    if ($row['Type'] == 'Admin') {
        $sub_array[] = '<span class="btn btn-block btn-warning btn-sm ">' . $row['Type'] . '</span>';
    } else if ($row['Type'] == 'Approver') {
        $sub_array[] = '<span class="btn btn-block btn-primary btn-sm ">'  . $row['Type'] . '</span>';
    } else if ($row['Type'] == 'staff'){
        $sub_array[] = '<span class="btn btn-block btn-success btn-sm ">'  . $row['Type'] . '</span>';
    } else {
        $sub_array[] = '<span class="btn btn-block btn-success btn-sm ">'  . $row['Type'] . '</span>';
    }




    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $total_all_rows,
    'recordsFiltered' => $total_all_rows,
    'data' => $data,
);
echo json_encode($output);
