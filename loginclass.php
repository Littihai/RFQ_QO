<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<?php

include("connect.php");

if (isset($_POST['Username']) && isset($_POST['Password'])) {

    $username = $_POST['Username'];
    $password = $_POST['Password'];




    $sqlT = "SELECT * FROM vw_Employee WHERE EmployeeUsername = '" . $username . "' ";
    $queryT = sqlsrv_query($conn, $sqlT);
    $resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC);

    if (!$resultT) {
        echo "<script type=\"text/javascript\">alert(\"ไม่สามารถใช้งานระบบได้ กรุณาติดต่อ HR เพื่อใช้งาน\");</script>";
        echo "<META HTTP-EQUIV='Refresh' CONTENT = '0;URL=index.php'>";
    } else {

        $adServer = "LDAP://TSE.COM";
        $ldap = ldap_connect($adServer);

        $ldaprdn =   $username . '@tse.com';

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($ldap, $ldaprdn, $password);

        // echo $resultT["EmployeeCode"];

        //echo "<script type=\"text/javascript\">alert(\"Incorrect Password , Please try again.\");</script>";

        if ($bind) {


            session_start();

            $_SESSION["Username"] = $username;
            $_SESSION["DepartmentCode"] = $resultT["DepartmentCode"];
            $_SESSION["CostCenter"] = $resultT["CostCenter"];


            Header("Location: Dashboard.php");
            @ldap_close($ldap);
        } else {
            echo "<script type=\"text/javascript\">alert(\"Username Password ไม่ถูกต้อง\");</script>";
            echo "<META HTTP-EQUIV='Refresh' CONTENT = '0;URL=index.php'>";
        }
    }
} else {
    echo "<script type=\"text/javascript\">alert(\"เกิดข้อผิลพลาด กรุณาเข้าสู่ระบบอีกครั้ง\");</script>";
    echo "<META HTTP-EQUIV='Refresh' CONTENT = '0;URL=index.php'>";
}

?>