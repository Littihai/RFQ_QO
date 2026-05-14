<?php
include("connect.php");
if (isset($_GET["quatation_file_id"])) {

    $quatation_file_id = $_GET['quatation_file_id'];
    $File_name = $_GET['File_name'];
    $quatation_ID = $_GET['quatation_ID'];


    $sql = "DELETE FROM quatation_file where quatation_file_id ='$quatation_file_id'";
    unlink("upload/" . $File_name);

    sqlsrv_query($conn, "SET NAMES UTF8");
    $query = sqlsrv_query($conn, $sql);


    if ($query) {

        session_start();
        $_SESSION['plan'] = " Delete file Successfully !";
        header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
    } else {
        echo "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
    }
}
exit;
sqlsrv_close($conn);
