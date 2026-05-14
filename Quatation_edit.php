<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Purchase</title>
    <style>
    form {
        margin: 20px 0;
    }

    form input,
    button {
        padding: 5px;
    }

    table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid #cdcdcd;
    }

    table th,
    table td {
        padding: 10px;
        text-align: left;
    }

    #loader {
        position: fixed;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0.7;
        background-color: white;
        z-index: 99;
    }

    #loader-image {
        z-index: 100;
        width: 250px;
        height: 250px;
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }


    .animate-bottom {
        position: relative;
        -webkit-animation-name: animatebottom;
        -webkit-animation-duration: 1s;
        animation-name: animatebottom;
        animation-duration: 1s
    }

    @-webkit-keyframes animatebottom {
        from {
            bottom: -100px;
            opacity: 0
        }

        to {
            bottom: 0px;
            opacity: 1
        }
    }

    @keyframes animatebottom {
        from {
            bottom: -100px;
            opacity: 0
        }

        to {
            bottom: 0;
            opacity: 1
        }
    }

    #myDiv {
        display: none;

    }

    .tooltip {
        position: relative;
        display: inline-block;

        border-bottom: 1px black;


    }


    /* arrow */


    .arrow-1 {
        margin-top: 8px;

        width: 80px;
        height: 30px;
        display: flex;
    }

    .arrow-1:before {
        content: "";
        background: currentColor;
        width: 15px;
        clip-path: polygon(0 10px, calc(100% - 15px) 10px, calc(100% - 15px) 0, 100% 50%, calc(100% - 15px) 100%, calc(100% - 15px) calc(100% - 10px), 0 calc(100% - 10px));
        animation: a1 1.5s infinite linear;
    }

    @keyframes a1 {

        90%,
        100% {
            flex-grow: 1
        }
    }
    </style>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->

    <script src="js/jquery-3.5.1.min.js"></script>


</head>

<body>

</body>

</html>

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

$DepartmentCode = $_SESSION["DepartmentCode"];
$quatation_ID = $_GET['quatation_ID'];

include("connect.php");
// $Username = 'Wittaya.Kha';

$sqlT2 = "SELECT * FROM vw_Employee where EmployeeUsername = '$Username' ";
$queryT2 = sqlsrv_query($conn, $sqlT2);
$resultT2 = sqlsrv_fetch_array($queryT2, SQLSRV_FETCH_ASSOC);
if (!$resultT2) {
   // echo "Error while fetching array.\n";
   die(print_r(sqlsrv_errors(), true));
} else if ($resultT2 === null) {
   echo "No results were found.\n";
} else {
   do {
      $EmployeeCode = $resultT2["EmployeeCode"];
      $EmployeeThFirstName = $resultT2["ThFirstName"];
      $EmployeeThLastName = $resultT2["ThLastName"];
   } while ($resultT2 = sqlsrv_fetch_array($queryT2, SQLSRV_FETCH_ASSOC));
}



$sqlAdmin = "SELECT * FROM admin_purchase where EmployeeCode = '$EmployeeCode' ";
$queryAdmin = sqlsrv_query($conn, $sqlAdmin);
$resultAdmin = sqlsrv_fetch_array($queryAdmin, SQLSRV_FETCH_ASSOC);
if (!$resultAdmin) {
} else if ($resultAdmin === null) {
   echo "No results were found.\n";
   $statusAdmin = 'no';
} else {
   $statusAdmin = 'yes';
}

?>
<!-- ==========================Add Start Date When Click Edit 04726 20260401=================================== -->
<?php
session_start();
include("connect.php");

$rfqNum = $_GET['rfq'];

// เช็คสิทธิ์ Admin
if ($statusAdmin == 'yes') {

    $username = $EmployeeCode;

    $sql = "UPDATE TSE_CateLeadTime
        SET 
            StartDate_LT = GETDATE(),
            UpdateBy = ?,
            UpdateDate = GETDATE()
        WHERE RFQNum = ?
        AND StartDate_LT IS NULL
    ";

    $params = array($username, $rfqNum);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $stmt = sqlsrv_query($conn, $sql, array($rfqNum));
}
?>
<!-- ================================================================ -->
<!DOCTYPE html>
<html lang="en">


<head>

    <!-- Required meta tags-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">




    <!-- Title Page-->
    <title>Request for quotation</title>

    <!-- Fontfaces CSS-->

    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">


    <!-- <script type="text/javascript" charset="utf8" src="js/jquery-3.5.1.js"></script> -->
    <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf8" src="js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/buttons.print.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/dataTables.rowGroup.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/buttons.colVis.min.js"></script>
    <!-- input file-->
    <script type="text/javascript" charset="utf8" src="js/piexif.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/sortable.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/fileinput.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/LANG.js"></script>
    <!--  -->

    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="css/rowGroup.dataTables.min.css">

    <!-- Include Bootstrap Datepicker -->
    <link rel="stylesheet" href="css/font-awesome.css" />
    <link rel="stylesheet" href="css/bootstrap-datepicker.min.css" />
    <script src="js/bootstrap-datepicker.min.js"></script>



</head>

<!-- <body class="animsition"> -->

<body onload="myFunction()" style="margin:0;  
  background-image: url('images/imageedit_1_5977293344.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: 100% 110%;
  z-index: -10;">

    <div id="loader">
        <img id="loader-image" src="DoubleRing-1s-200px.gif" alt="Loading..." /><br />
    </div>

    <div id="myDiv" class="animate-bottom">
        <div class="page-wrapper" style="background-color: #ffffff00; overflow-y: hidden;">
            <!-- HEADER MOBILE-->
            <header class="header-mobile d-block d-lg-none">
                <div class="header-mobile__bar">
                    <div class="container-fluid">
                        <div class="header-mobile-inner">
                            <a class="logo" href="index.html">
                                <h1>Request for quotation</h1>
                                <!-- <img src="images/icon/logo.png" alt="CoolAdmin" /> -->
                            </a>
                            <button class="hamburger hamburger--slider" type="button">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <nav class="navbar-mobile">
                    <div class="container-fluid">
                        <ul class="navbar-mobile__list list-unstyled">
                            <li>
                                <a href="Dashboard.php">
                                    <i class="fas fa-home"></i>Dashboard</a>
                            </li>

                            <li class="active has-sub">
                                <a href="#">
                                    <i class="fas fa-align-justify"></i>Quotation</a>
                            </li>

                            <?php if ($statusAdmin == 'yes') { ?>
                            <li>
                                <a href="Admin_edit.php">
                                    <i class="fa fa-lock"></i>Edit Admin</a>
                            </li>
                            <?php } ?>

                            <?php 
                            $token = base64_encode($EmployeeCode); 
                            ?>

                            <?php if ($statusAdmin == 'yes') { ?>
                                <li>
                                    <a href="https://web.ts-engineering.com/TSE_Upload_RFQ/home/category?token=<?php echo $token; ?>" target="_blank">
                                        <i class="fa fa-user"></i> Category
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($statusAdmin == 'yes') { ?>
                                <li>
                                    <a href="https://web.ts-engineering.com/TSE_Upload_RFQ/home/upload?token=<?php echo $token; ?>" target="_blank">
                                        <i class="fa fa-upload"></i> Upload
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($statusAdmin == 'yes') { ?>
                                <li>
                                    <a href="https://web.ts-engineering.com/TSE_Upload_RFQ/home/Search?token=<?php echo $token; ?>" target="_blank">
                                        <i class="fa fa-search" aria-hidden="true"></i> Search
                                    </a>
                                </li>
                            <?php } ?>

                            <li>
                                <a href="logout.php">
                                    <i class="fas fa-power-off"></i>Log out</a>
                            </li>

                        </ul>
                    </div>
                </nav>
            </header>
            <!-- END HEADER MOBILE-->

            <!-- MENU SIDEBAR-->
            <aside class="menu-sidebar d-none d-lg-block">
                <div class="logo">
                    <a href="#">
                        <h3>Request for quotation</h3> <!-- <img src="images/icon/logo.png" alt="Cool Admin" /> -->
                    </a>
                </div>
                <div class="menu-sidebar__content js-scrollbar1">
                    <nav class="navbar-sidebar">
                        <ul class="list-unstyled navbar__list">
                            <li>
                                <a href="Dashboard.php">
                                    <i class="fas fa-home"></i>Dashboard</a>
                            </li>
                            <li class="active has-sub">
                                <a href="#">
                                    <i class="fas fa-align-justify"></i>Quotation</a>
                            </li>
                            <?php if ($statusAdmin == 'yes') { ?>
                            <li>
                                <a href="Admin_edit.php">
                                    <i class="fa fa-lock"></i>Edit Admin</a>
                            </li>
                            <?php } ?>

                            <?php 
                            $token = base64_encode($EmployeeCode); 
                            ?>

                            <?php if ($statusAdmin == 'yes') { ?>
                                <li>
                                    <a href="https://web.ts-engineering.com/TSE_Upload_RFQ/home/category?token=<?php echo $token; ?>" target="_blank">
                                        <i class="fa fa-user"></i> Category
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($statusAdmin == 'yes') { ?>
                                <li>
                                    <a href="https://web.ts-engineering.com/TSE_Upload_RFQ/home/upload?token=<?php echo $token; ?>" target="_blank">
                                        <i class="fa fa-upload"></i> Upload
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($statusAdmin == 'yes') { ?>
                                <li>
                                    <a href="https://web.ts-engineering.com/TSE_Upload_RFQ/home/Search?token=<?php echo $token; ?>" target="_blank">
                                        <i class="fa fa-search" aria-hidden="true"></i> Search
                                    </a>
                                </li>
                            <?php } ?>

                            <li>
                                <a href="logout.php">
                                    <i class="fas fa-power-off"></i>Log out</a>
                            </li>
                            <div style="position: absolute;  bottom: 10px;">
                                <li>
                                    <div class="account-wrap">
                                        <div class="account-item clearfix js-item-menu">

                                            <a href="#">
                                                <?php


                                    include("connect.php");

                                    $sqlT = "SELECT * FROM vw_Employee where EmployeeUsername = '$Username' ";
                                    $queryT = sqlsrv_query($conn, $sqlT);
                                    $resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC);
                                    if (!$resultT) {
                                       // echo "Error while fetching array.\n";
                                       die(print_r(sqlsrv_errors(), true));
                                    } else if ($resultT === null) {
                                       echo "No results were found.\n";
                                    } else { 
                                       do { ?>

                                                <i class="fas fa-user"></i><?php echo $resultT["ThFirstName"]; ?>
                                                <?php echo $resultT["ThLastName"]; ?>
                                                <?php echo $resultT["EmployeeCode"]; ?>

                                            </a>






                                        </div>
                                    </div>
                                </li>
                            </div>
                        </ul>
                    </nav>
                </div>
            </aside>
            <!-- END MENU SIDEBAR-->

            <!-- PAGE CONTAINER-->
            <div class="page-container" style="background-color: transparent;">
                <!-- MAIN CONTENT-->
                <div class="main-content">
                    <div class="section__content section__content--p30">
                        <div class="container-fluid">

                            <!-- โชว์ alert -->
                            <?php
                                session_start();
                                if (isset($_SESSION['plan'])) {

                                    if ($_SESSION['plan_status'] == 'error') {
                                    echo '<div class="alert alert-danger alert-dismissable" style="display:none; z-index:999; position: fixed;
                                    top: 1em;
                                    right: 1em;
                                    width: 75%;" id="flash-msg">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <i class="icon fa fa-exclamation-triangle"></i> ' . $_SESSION['plan'] . '</div>';
                                    } else {
                                    echo '<div class="alert alert-success alert-dismissable" style="display:none; z-index:999; position: fixed;
                                    top: 1em;
                                    right: 1em;
                                    width: 75%;" id="flash-msg">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <i class="icon fa fa-check"></i> ' . $_SESSION['plan'] . '</div>';
                                    }
                                }
                                unset($_SESSION['plan']);
                                unset($_SESSION['plan_status']);
                            ?>

                            <script>
                            $(document).ready(function() {
                                $("#flash-msg").delay(1500).fadeIn(800).delay(20000).fadeOut("slow");
                            });
                            </script>
                            <!-- END โชว์ alert -->

                            <div class="row">
                                <div class="col-lg-12">
                                    <div id="snoAlertBox"
                                        class="sufee-alert alert with-close alert-primary alert-dismissible fade show "
                                        style=" position:fixed; z-index: 999; width:60%; margin-left: 10px ; margin-top: auto; display: none;">
                                        <span class="badge badge-pill badge-primary">Success</span>
                                        You successfully read this important alert.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <h2 class="m-b-20" style="color: white;">Quotation</h2>

                                    <div class="top-campaign" style="padding-bottom: 45px;">

                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="card-title">
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-4"></div>
                                                        <div class="col-md-6 col-sm-4">
                                                            <h3 class="text-center title-10"><i class="fa fa-file-text"
                                                                    aria-hidden="true"></i> Request for Quotation</h3>
                                                        </div>

                                                        <?php
                                                        
                                                        $queryT = "SELECT quatation.*,status.*,RFQType.Id, RFQType.RFQ_Type FROM quatation LEFT JOIN status ON quatation.status = status.status_id Left Join RFQType On quatation.tse_rfq_type = RFQType.Id WHERE quatation_ID = '$quatation_ID' "; //20220124 add by 04404
                                                        // $queryT = "SELECT quatation.*,status.* FROM quatation LEFT JOIN status ON quatation.status = status.status_id WHERE quatation_ID = '$quatation_ID' ";
                                                        $returnedValueT = sqlsrv_query($conn, $queryT);
                                                        $rowT = sqlsrv_fetch_array($returnedValueT, SQLSRV_FETCH_ASSOC);

                                                        if ($rowT === false) {

                                                            die(print_r(sqlsrv_errors(), true));
                                                        } else if ($rowT === null) {
                                                            echo "No results were found.\n";
                                                        } else {
                                                            do {
                                                                $employee_code_request = $rowT["employee_code_request"];
                                                                $pu_nameTH = $rowT["pu_nameTH"];
                                                                if ($rowT["date_picker"] != NULL) {
                                                                $date_picker = date("d/m/Y", strtotime($rowT['date_picker']->format('Y-m-d')));
                                                                } else {
                                                                $date_picker = '';
                                                                }
                                                                
                                                        ?>

                                                        <div class="col-md-3 col-sm-4">
                                                            <input type="text" class="form-control"
                                                                value="<?php echo  $rowT["num_req"] ?>" disabled>

                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- end num_req by 04404 -->

                                                <?php
                                                include("connect.php");

                                                $sqlD = "SELECT * FROM vw_Employee where EmployeeCode = '$employee_code_request' ";
                                                $queryD = sqlsrv_query($conn, $sqlD);
                                                $resultD = sqlsrv_fetch_array($queryD, SQLSRV_FETCH_ASSOC);
                                                if (!$resultD) {
                                                   // echo "Error while fetching array.\n";
                                                   die(print_r(sqlsrv_errors(), true));
                                                } else if ($resultD === null) {
                                                   echo "No results were found.\n";
                                                } else {
                                                    do {
                                                        $EngFirstName = $resultD["EngFirstName"];
                                                        $EmployeePlantCode = $resultD["PlantCode"];
                                                        $DepartmentCode = $resultD["DepartmentCode"];
                                                ?>
                                                <form action="db_save_quatation_edit.php" name="save" id="save-form"
                                                    method="post" ENCTYPE="multipart/form-data">
                                                    <!-- <form> -->
                                                        <input id="num_req" name="num_req" type="text"
                                                            value="<?php echo  $numRequire ?>" hidden>
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <strong class="card-title"><label
                                                                            for="employee_code_request"
                                                                            class="control-label mb-1"><i
                                                                                class="fa fa-address-card-o"
                                                                                aria-hidden="true"></i> Employee
                                                                            Code</label></strong>
                                                                    <input id="employee_code_request"
                                                                        name="employee_code_request"
                                                                        value="<?php echo $resultD["EmployeeCode"] ?>"
                                                                        type="text" class="form-control"
                                                                        aria-required="true" aria-invalid="false"
                                                                        readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <strong class="card-title"><label for="name_request"
                                                                            class="control-label mb-1"><i
                                                                                class="fa fa-user-circle-o"
                                                                                aria-hidden="true"></i> Requester's
                                                                            name</label></strong>
                                                                    <input id="name_request" name="name_request"
                                                                        value="<?php echo $resultD["ThFullName"] ?>"
                                                                        type="text" class="form-control"
                                                                        aria-required="true" aria-invalid="false"
                                                                        readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group has-success">
                                                                    <strong class="card-title"><label for="department"
                                                                            class="control-label mb-1"><i
                                                                                class="fa fa-home"
                                                                                aria-hidden="true"></i>
                                                                            Department</label></strong>
                                                                    <input id="department" name="department"
                                                                        value="<?php echo $resultD["DepartmentName"] ?>"
                                                                        type="text"
                                                                        class="form-control department valid"
                                                                        aria-required="true" aria-invalid="false"
                                                                        readonly>

                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <strong class="card-title"><label for="tel"
                                                                            class="control-label mb-1"><i
                                                                                class="fa fa-phone"
                                                                                aria-hidden="true"></i>
                                                                            Tel</label></strong>
                                                                    <input id="tel" name="tel" type="tel"
                                                                        value="<?php echo $resultD["ContactNo"] ?>"
                                                                        class="form-control tel" data-val="true"
                                                                        data-val-required="Please enter tel number"
                                                                        data-val-tel="Please enter a valid tel number"
                                                                        autocomplete="tel" readonly>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <strong class="card-title"><label for="email"
                                                                            class="control-label mb-1"><i
                                                                                class="fa fa-envelope"
                                                                                aria-hidden="true"></i>
                                                                            Email</label></strong>
                                                                    <input id="email" name="email" type="email"
                                                                        value="<?php echo $resultD["Email"] ?>"
                                                                        class="form-control email" value=""
                                                                        data-val="true"
                                                                        data-val-required="Please enter your email"
                                                                        data-email="Please enter email"
                                                                        autocomplete="email" readonly>
                                                                    <span class="help-block" data-valmsg-for="email"
                                                                        data-valmsg-replace="true"></span>
                                                                </div>
                                                            </div>
                                                            <?php if(0){ //$rowT['status'] == 6 || $rowT['status'] == 11 || $rowT['status'] == 12 //20220213 comment by 04404 ?>
                                                            <div class="col-md-6 col-sm-12">
                                                                <strong><label for="rfq_type" class="form-select mb-1"><i
                                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                        RFQ. Type</label></strong>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <label class="input-group-text" for="rfq_type">RFQ.
                                                                            Type</label>
                                                                    </div>
                                                                    <select class="custom-select" id="rfq_type" name="rfq_type">
                                                                        <?php
                                                                        include("connect.php");

                                                                        $sqlType = "SELECT * FROM RFQType where Flag_Active = '1' Order By Id ASC ";
                                                                        $queryType = sqlsrv_query($conn, $sqlType);
                                                                        $resultType = sqlsrv_fetch_array($queryType, SQLSRV_FETCH_ASSOC);
                                                                        if (!$resultType) {
                                                                            echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                                            echo "<script>window.location.href='Dashboard.php';</script>";
                                                                            exit;
                                                                        } else if ($resultType === null) {
                                                                            echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                                            echo "<script>window.location.href='Dashboard.php';</script>";
                                                                            exit;
                                                                        } else {
                                                                            do { ?>

                                                                            <?php if($resultType["Id"] == $rowT["tse_rfq_type"]){ ?>
                                                                                <option value="<?php echo $resultType["Id"]; ?>" selected>
                                                                                <?php echo $resultType["RFQ_Type"]; ?> </option>
                                                                            <?php }else{  ?>
                                                                                <option value="<?php echo $resultType["Id"]; ?>">
                                                                                <?php echo $resultType["RFQ_Type"]; ?> </option>
                                                                            <?php }  ?> 

                                                                        <?php
                                                                            } while ($resultType = sqlsrv_fetch_array($queryType, SQLSRV_FETCH_ASSOC));
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <?php } else{ ?>
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <strong class="card-title"><label for="rfq_type"
                                                                            class="control-label mb-1"><i
                                                                                class="fa fa-file-text-o"
                                                                                aria-hidden="true"></i> RFQ.
                                                                            Type</label></strong>
                                                                    <input id="rfq_type" name="rfq_type"
                                                                        value="<?php echo $rowT["RFQ_Type"] ?>"
                                                                        type="text" class="form-control"
                                                                        aria-required="true" aria-invalid="false"
                                                                        readonly>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                            
                                                        </div>                                                       

                                                        <?php  } while ($resultD = sqlsrv_fetch_array($queryD, SQLSRV_FETCH_ASSOC));
                                                        } ?>

                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12">


                                                                <!-- <div class="form-group">
                                                                    <strong class="card-title"><label  for="date_picker" class="control-label mb-1">วันที่ต้องการ</label></strong>
                                                                    <input type="date" class="form-control" name="date_picker" id="date_picker" required>
                                                                </div> -->
                                                                <div class="form-group">
                                                                    <strong class="card-title"><label
                                                                            for="date_picker"><i class="fa fa-calendar"
                                                                                aria-hidden="true"></i> Expected
                                                                            date</label></strong>
                                                                    <div class="input-group">
                                                                        <input class="datepicker form-control"
                                                                            data-provide="datepicker" id="datepicker"
                                                                            name="datepicker" autocomplete="off"
                                                                            value="<?php echo $date_picker ?>" disabled>
                                                                        <div class="input-group-btn">
                                                                            <span class="btn btn-secondary"><i
                                                                                    class="fa fa-calendar"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <strong class="card-title"><label
                                                                            for="Status_active"
                                                                            class="control-label mb-1"><i
                                                                                class="fa fa-tasks"
                                                                                aria-hidden="true"></i>
                                                                            Status</label></strong>
                                                                    <input id="Status_active" name="Status_active"
                                                                        value="<?php echo $rowT["status_name"]; ?>"
                                                                        class="form-control" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end main rfq: req_num, request_name, dept, tel, email, req_type, expected_dae, status by 04404 -->

                                                        <!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx      UPLOAD PDF    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->
                                                        <!-- <div class="row" style="margin-top:10px;">
                                                            <div class="col-12">
                                                                <label for="file_pdf" class="form-label">Upload File PDF (รวมเป็นไฟล์เดียว) : </label>
                                                                <input type='file' name='file_pdf' id='file_pdf' />
                                                            </div>
                                                        </div> -->
                                                        <div class="card">
                                                            <div class="card-header">

                                                                <strong class="card-title"><label for="approve_code"
                                                                        class="control-label mb-30">Work process
                                                                        (%)</label></strong>
                                                                <!-- >>>>>>>>>>>>>>>>>>>>>>>>> Work process  <<<<<<<<<<<<<<<<<<<<< -->
                                                                <div class="progress" style="margin-top:5px">
                                                                    <?php if ($rowT['status'] == 1) { ?>
                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 15%"
                                                                        aria-valuenow="15" aria-valuemin="0"
                                                                        aria-valuemax="100">15%</div>
                                                                    <?php } elseif ($rowT['status'] == 2) { ?>
                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 30%"
                                                                        aria-valuenow="30" aria-valuemin="0"
                                                                        aria-valuemax="100">30%</div>
                                                                    <?php } elseif ($rowT['status'] == 9) { ?>
                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 45%"
                                                                        aria-valuenow="45" aria-valuemin="0"
                                                                        aria-valuemax="100">45%</div>
                                                                    <?php } elseif ($rowT['status'] == 10) { ?>
                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 60%"
                                                                        aria-valuenow="60" aria-valuemin="0"
                                                                        aria-valuemax="100">60%</div>
                                                                    <?php } elseif ($rowT['status'] == 3) { ?>
                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 75%"
                                                                        aria-valuenow="75" aria-valuemin="0"
                                                                        aria-valuemax="100">75%</div>
                                                                    <?php } elseif ($rowT['status'] == 4) { ?>
                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 100%"
                                                                        aria-valuenow="100" aria-valuemin="0"
                                                                        aria-valuemax="100">100%</div>

                                                                    <?php } elseif ($rowT['status'] == 5) {
                                                                        if ($rowT["work_process_status_user"] == 'unsuccess') { ?>
                                                                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 15%"
                                                                        aria-valuenow="15" aria-valuemin="0"
                                                                        aria-valuemax="100">15%</div>

                                                                    <?php   } elseif ($rowT["work_process_status_approvepu"] == 'unsuccess') { ?>
                                                                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 15%"
                                                                        aria-valuenow="15" aria-valuemin="0"
                                                                        aria-valuemax="100">15%</div>

                                                                    <?php   } elseif ($rowT["work_process_status_pu"] == 'unsuccess') { ?>
                                                                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 75%"
                                                                        aria-valuenow="75" aria-valuemin="0"
                                                                        aria-valuemax="100">75%</div>

                                                                    <?php   } else { ?>
                                                                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 100%"
                                                                        aria-valuenow="100" aria-valuemin="0"
                                                                        aria-valuemax="100">100%</div>
                                                                    <?php }

                                                                    } elseif ($rowT['status'] == 6 || $rowT['status'] == 11 || $rowT['status'] == 12) { ?>
                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 15%"
                                                                        aria-valuenow="15" aria-valuemin="0"
                                                                        aria-valuemax="100">15%</div>
                                                                    <?php } elseif ($rowT['status'] == 7) { ?>
                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width:75%"
                                                                        aria-valuenow="75" aria-valuemin="0"
                                                                        aria-valuemax="100">75%</div>
                                                                    <?php } elseif ($rowT['status'] == 8) { ?>
                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width:90%"
                                                                        aria-valuenow="90" aria-valuemin="0"
                                                                        aria-valuemax="100">90%</div>

                                                                    <?php } else { ?>
                                                                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated"
                                                                        role="progressbar" style="width: 100%"
                                                                        aria-valuenow="100" aria-valuemin="0"
                                                                        aria-valuemax="100">100%</div>
                                                                    <?php } ?>


                                                                </div>
                                                            </div>

                                                            <!-- >>>>>>>>>>>>>>>>>>>>>>>>> Status <<<<<<<<<<<<<<<<<<<<< -->
                                                            <div class="card-body">
                                                                <!-- >>>>>>>>>>>>>>>>>>>>>>>>> Approve User Status <<<<<<<<<<<<<<<<<<<<< -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approver_user_nameTH"
                                                                                    class="control-label mb-1">Purchase Staff</label></span>
                                                                                    <!-- class="control-label mb-1">Department Manager</label></span> --> <!-- Comment by 04726 20260401-->
                                                                            <input id="approver_user_nameTH"
                                                                                name="approver_user_nameTH"
                                                                                value="<?php echo $rowT['approver_user_nameTH']; ?>"
                                                                                class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group d-none d-md-block">
                                                                            <span class="card-title"><label
                                                                                    for="arrow-1"
                                                                                    class="control-label mb-1"></label></span>
                                                                            <div class="arrow-1" id="arrow-1"></div>
                                                                        </div>
                                                                    </div>
                                                                    <?php if ($rowT['work_process_status_user'] == 'success') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Staff</label></span>
                                                                                    <!-- status of Department Manager</label></span>--> <!-- Comment by 04726 20260401-->
                                                                            <span class="btn btn-success btn-block"
                                                                                id="ApproveStatus">Approved @
                                                                                <?php echo date("d/m/Y H:i:s", strtotime($rowT['date_time_stamp_approver_user']->format('Y-m-d H:i:s'))); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <?php  } elseif ($rowT['work_process_status_user'] == 'unsuccess') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Staff</label></span>
                                                                                    <!-- status of Department Manager</label></span>--> <!-- Comment by 04726 20260401-->
                                                                            <span class="btn btn-info btn-block"
                                                                                id="ApproveStatus">Wait for
                                                                                K.<?php echo $EngFirstName ?> revise
                                                                                Quotation...</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['work_process_status_user'] == 'pending') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Staff</label></span>
                                                                                    <!-- status of Department Manager</label></span>--> <!-- Comment by 04726 20260401-->
                                                                            <span
                                                                                class="btn btn-primary btn-block progress-bar-striped progress-bar-animated"
                                                                                role="progressbar"
                                                                                id="ApproveStatus">Inprogress . .
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['work_process_status_user'] == 'skip') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Staff</label></span>
                                                                                    <!-- status of Department Manager</label></span>--> <!-- Comment by 04726 20260401-->
                                                                            <span class="btn btn-success btn-block"
                                                                                id="ApproveStatus">Skip</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                    <div class="col-md-6 col-sm-12"></div>
                                                                    <?php } ?>
                                                                </div>

                                                                <!-- >>>>>>>>>>>>>>>>>>>>>>>>> Approve GM Status <<<<<<<<<<<<<<<<<<<<< -->
                                                                <!-- <div class="row">
                                                                    <div class="col-md-4 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approver_gm_nameTH"
                                                                                    class="control-label mb-1">General
                                                                                    Manager</label></span>
                                                                            <input id="approver_gm_nameTH"
                                                                                name="approver_gm_nameTH"
                                                                                value="<?php echo $rowT['approver_gm_nameTH']; ?>"
                                                                                class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group d-none d-md-block">
                                                                            <span class="card-title"><label
                                                                                    for="arrow-1"
                                                                                    class="control-label mb-1"></label></span>
                                                                            <div class="arrow-1" id="arrow-1"></div>
                                                                        </div>
                                                                    </div>
                                                                    <?php if ($rowT['work_process_status_gm'] == 'success') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveGMStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of General
                                                                                    Manager</label></span>
                                                                            <span class="btn btn-success btn-block"
                                                                                id="ApproveGMStatus">Approved @
                                                                                <?php echo date("d/m/Y H:i:s", strtotime($rowT['date_time_stamp_approver_gm']->format('Y-m-d H:i:s'))); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <?php  } elseif ($rowT['work_process_status_gm'] == 'unsuccess') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveGMStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of General
                                                                                    Manager</label></span>
                                                                            <span class="btn btn-info btn-block"
                                                                                id="ApproveGMStatus">Wait for
                                                                                K.<?php echo $EngFirstName ?> revise
                                                                                Quotation...</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['work_process_status_gm'] == 'pending') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveGMStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of General
                                                                                    Manager</label></span>
                                                                            <span
                                                                                class="btn btn-primary btn-block progress-bar-striped progress-bar-animated"
                                                                                role="progressbar"
                                                                                id="ApproveGMStatus">Inprogress . .
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['work_process_status_gm'] == 'skip') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveGMStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of General
                                                                                    Manager</label></span>
                                                                            <span class="btn btn-success btn-block"
                                                                                id="ApproveGMStatus">Skip</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['work_process_status_gm'] == 'wait') { ?>                                                   
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveGMStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of General Manager
                                                                                </label></span>
                                                                            <span class="btn btn-secondary btn-block"
                                                                                id="ApproveGMStatus">Pending</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                    <div class="col-md-6 col-sm-12"></div>
                                                                    <?php } ?>
                                                                </div> --> 
                                                                <!-- comment by 04726 20260401: เนื่องจากตอนนี้ยังไม่มีขั้นตอนการอนุมัติของ GM ในระบบ จึงขอปิดไว้ก่อนนะครับ -->

                                                                <!-- >>>>>>>>>>>>>>>>>>>>>>>>> Approve MD Status <<<<<<<<<<<<<<<<<<<<< -->
                                                                <!-- <div class="row">
                                                                    <div class="col-md-4 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approver_md_nameTH"
                                                                                    class="control-label mb-1">Managing
                                                                                    Director</label></span>
                                                                            <input id="approver_md_nameTH"
                                                                                name="approver_md_nameTH"
                                                                                value="<?php echo $rowT['approver_md_nameTH']; ?>"
                                                                                class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group d-none d-md-block">
                                                                            <span class="card-title"><label
                                                                                    for="arrow-1"
                                                                                    class="control-label mb-1"></label></span>
                                                                            <div class="arrow-1" id="arrow-1"></div>
                                                                        </div>
                                                                    </div>
                                                                    <?php if ($rowT['work_process_status_md'] == 'success') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveMDStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Managing
                                                                                    Director</label></span>
                                                                            <span class="btn btn-success btn-block"
                                                                                id="ApproveMDStatus">Approved @
                                                                                <?php echo date("d/m/Y H:i:s", strtotime($rowT['date_time_stamp_approver_md']->format('Y-m-d H:i:s'))); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <?php  } elseif ($rowT['work_process_status_md'] == 'unsuccess') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveMDStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Managing
                                                                                    Director</label></span>
                                                                            <span class="btn btn-info btn-block"
                                                                                id="ApproveMDStatus">Wait for
                                                                                K.<?php echo $EngFirstName ?> revise
                                                                                Quotation...</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['work_process_status_md'] == 'pending') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveMDStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Managing
                                                                                    Director</label></span>
                                                                            <span
                                                                                class="btn btn-primary btn-block progress-bar-striped progress-bar-animated"
                                                                                role="progressbar"
                                                                                id="ApproveMDStatus">Inprogress . .
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['work_process_status_md'] == 'skip') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveMDStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Managing Director</label></span>
                                                                            <span class="btn btn-success btn-block"
                                                                                id="ApproveMDStatus">Skip</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['work_process_status_md'] == 'wait') { ?>                                                   
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="ApproveMDStatus"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Managing Director
                                                                                </label></span>
                                                                            <span class="btn btn-secondary btn-block"
                                                                                id="ApproveMDStatus">Pending</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                    <div class="col-md-6 col-sm-12"></div>
                                                                    <?php } ?>
                                                                </div> -->
                                                                <!-- // comment by 04726 20260401: เนื่องจากตอนนี้ยังไม่มีขั้นตอนการอนุมัติของ MD ในระบบ จึงขอปิดไว้ก่อนนะครับ -->

                                                                <!-- >>>>>>>>>>>>>>>>>>>>>>>>> Approve Mgr. Purchase Status <<<<<<<<<<<<<<<<<<<<< -->
                                                                <!-- <div class="row">
                                                                    <div class="col-md-4 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approver_pu_nameTH"
                                                                                    class="control-label mb-1">Purchase Manager</label></span>
                                                                            <input id="approver_pu_nameTH"
                                                                                name="approver_pu_nameTH"
                                                                                value="<?php echo $rowT['approver_pu_nameTH']; ?>"
                                                                                class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group d-none d-md-block">
                                                                            <span class="card-title"><label
                                                                                    for="arrow-1"
                                                                                    class="control-label mb-1"></label></span>
                                                                            <div class="arrow-1" id="arrow-1"></div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    if ($rowT['work_process_status_approvepu'] == 'pending') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approve_pu_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Manager
                                                                                </label></span>
                                                                            <span
                                                                                class="btn btn-primary btn-block progress-bar-striped progress-bar-animated"
                                                                                role="progressbar" id="
                                                                                approve_pu_status">Inprogress . .
                                                                                .</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    } elseif ($rowT['work_process_status_approvepu'] == 'success') {
                                                                        if ($rowT['status'] == '7') {
                                                                            // 7 = หลังจาก staff pur หาราคาได้แล้ว, mgr pur Disapproved, ให้ pur staff Revise ใหม่
                                                                        ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approve_pu_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Manager
                                                                                </label></span>
                                                                            <span class="btn btn-info btn-block"
                                                                                id="approve_pu_status">Wait for
                                                                                K.<?php echo $pu_nameTH ?> revise
                                                                                Quotation...</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['status'] == '8') {  
                                                                        // 8 = staff pur หาราคาได้แล้ว, รอ mgr pur อนุมัติ
                                                                        ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approve_pu_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Manager
                                                                                </label></span>
                                                                            <span
                                                                                class="btn btn-primary btn-block progress-bar-striped progress-bar-animated"
                                                                                role="progressbar" id="
                                                                                approve_pu_status">Inprogress . .
                                                                                .</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['status'] == '5') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approve_pu_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Manager
                                                                                </label></span>
                                                                            <span class="btn btn-success btn-block"
                                                                                id="approve_pu_status">Success @
                                                                                <?php echo date("d/m/Y H:i:s", strtotime($rowT['date_time_stamp_update']->format('Y-m-d H:i:s'))); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approve_pu_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Manager
                                                                                </label></span>
                                                                            <span class="btn btn-success btn-block"
                                                                                id="approve_pu_status">Approved @
                                                                                <?php echo date("d/m/Y H:i:s", strtotime($rowT['date_time_stamp_approver_pu']->format('Y-m-d H:i:s'))); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <?php }
                                                                    // end status: success
                                                                    } elseif ($rowT['work_process_status_approvepu'] == 'unsuccess') { ?>
                                                                    <?php if ($rowT['status'] == '7') {
                                                                        ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approve_pu_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Manager
                                                                                </label></span>

                                                                            <span class="btn btn-info btn-block"
                                                                                id="approve_pu_status">Wait for
                                                                                K.<?php echo $pu_nameTH ?> revise
                                                                                Quotation...</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['status'] == '8') {
                                                                        ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approve_pu_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Manager
                                                                                </label></span>
                                                                            <span
                                                                                class="btn btn-primary btn-block progress-bar-striped progress-bar-animated"
                                                                                role="progressbar" id="
                                                                                approve_pu_status">Inprogress
                                                                                ...</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approve_pu_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Manager
                                                                                </label></span>
                                                                            <span class="btn btn-danger btn-block"
                                                                                id="approve_pu_status">Unsuccess @
                                                                                <?php echo date("d/m/Y H:i:s", strtotime($rowT['date_time_stamp_approver_pu']->format('Y-m-d H:i:s'))); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <?php }
                                                                    } elseif ($rowT['work_process_status_approvepu'] == 'wait') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="approve_pu_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Manager
                                                                                </label></span>
                                                                            <span class="btn btn-secondary btn-block"
                                                                                id="approve_pu_status">Pending</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } ?>

                                                                </div> -->
                                                                <!-- // comment by 04726 20260401: เนื่องจากตอนนี้ยังไม่มีขั้นตอนการอนุมัติของ Mgr. Purchase ในระบบ จึงขอปิดไว้ก่อนนะครับ -->
                                                                <!-- >>>>>>>>>>>>>>>>>>>>>>>>> Approve Staff Purchase Status <<<<<<<<<<<<<<<<<<<<< -->
                                                                <!-- <div class="row">
                                                                    <div class="col-md-4 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label for="PU"
                                                                                    class="control-label mb-1">Purchase Staff</label></span>
                                                                            <input id="PU" name="PU"
                                                                                value="<?php echo $rowT['pu_nameTH']; ?>"
                                                                                class="form-control" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-2">
                                                                        <div class="form-group d-none d-md-block">
                                                                            <span class="card-title"><label
                                                                                    for="arrow-1"
                                                                                    class="control-label mb-1"></label></span>
                                                                            <div class="arrow-1" id="arrow-1"></div>
                                                                        </div>
                                                                    </div>
                                                                    <?php if ($rowT['work_process_status_pu'] == 'success') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="Pu_work_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Staff</label></span>

                                                                            <span class="btn btn-success btn-block"
                                                                                id="Pu_work_status">Success @
                                                                                <?php echo date("d/m/Y H:i:s", strtotime($rowT['date_time_stamp_pu']->format('Y-m-d H:i:s'))); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <?php  } elseif ($rowT['work_process_status_pu'] == 'unsuccess') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="Pu_work_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Staff</label></span>
                                                                            <span class="btn btn-danger btn-block"
                                                                                id="Pu_work_status">Unsuccess @
                                                                                <?php echo date("d/m/Y H:i:s", strtotime($rowT['date_time_stamp_update']->format('Y-m-d H:i:s'))); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['work_process_status_pu'] == 'pending') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="Pu_work_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Staff</label></span>
                                                                            <span
                                                                                class="btn btn-primary btn-block progress-bar-striped progress-bar-animated"
                                                                                role="progressbar" id="
                                                                                Pu_work_status">Inprogress . . .</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } elseif ($rowT['work_process_status_pu'] == 'wait') { ?>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <span class="card-title"><label
                                                                                    for="Pu_work_status"
                                                                                    class="control-label mb-1 ">Working
                                                                                    status of Purchase Staff</label></span>
                                                                            <span class="btn btn-secondary btn-block"
                                                                                id="Pu_work_status">Pending</span>
                                                                        </div>
                                                                    </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                        <!-- // comment by 04726 20260401: เนื่องจากตอนนี้ยังไม่มีขั้นตอนการอนุมัติของ Staff Purchase ในระบบ จึงขอปิดไว้ก่อนนะครับ -->

                                                       
                                                        <!-- Comment -->
                                                        <div class="row" style="padding: 0px;">
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <strong class="card-title"><label for="nav-tab"
                                                                                class="control-label mb-1"><i
                                                                                    class="fa fa-commenting-o"
                                                                                    aria-hidden="true"></i>
                                                                                Comment</label></strong>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="default-tab">
                                                                            <nav>
                                                                                <div class="nav nav-tabs" id="nav-tab"
                                                                                    role="tablist">
                                                                                    <?php if ($status == '5') { ?>
                                                                                    <a class="nav-item nav-link"
                                                                                        id="nav-user-tab"
                                                                                        data-toggle="tab"
                                                                                        href="#nav-user" role="tab"
                                                                                        aria-controls="nav-user"
                                                                                        aria-selected="true">User</a>
                                                                                    <a class="nav-item nav-link"
                                                                                        id="nav-approver-tab"
                                                                                        data-toggle="tab"
                                                                                        href="#nav-approver" role="tab"
                                                                                        aria-controls="nav-approver"
                                                                                        aria-selected="false">Approver
                                                                                        DM</a>
                                                                                    <a class="nav-item nav-link"
                                                                                        id="nav-approver-gm-tab"
                                                                                        data-toggle="tab"
                                                                                        href="#nav-approver-gm" role="tab"
                                                                                        aria-controls="nav-approver-gm"
                                                                                        aria-selected="false">Approver
                                                                                        GM</a>
                                                                                    <a class="nav-item nav-link"
                                                                                        id="nav-approver-md-tab"
                                                                                        data-toggle="tab"
                                                                                        href="#nav-approver-md" role="tab"
                                                                                        aria-controls="nav-approver-md"
                                                                                        aria-selected="false">Approver
                                                                                        MD</a>
                                                                                    <a class="nav-item nav-link active"
                                                                                        id="nav-aprrover-pu-tab"
                                                                                        data-toggle="tab"
                                                                                        href="#nav-aprrover-pu"
                                                                                        role="tab"
                                                                                        aria-controls="nav-aprrover-pu"
                                                                                        aria-selected="false">Approver
                                                                                        Purchase</a>
                                                                                    <a class="nav-item nav-link"
                                                                                        id="nav-pu-tab"
                                                                                        data-toggle="tab" href="#nav-pu"
                                                                                        role="tab"
                                                                                        aria-controls="nav-pu"
                                                                                        aria-selected="false">Purchase
                                                                                        Staff</a>

                                                                                    <?php } else { ?>
                                                                                    <a class="nav-item nav-link active"
                                                                                        id="nav-user-tab"
                                                                                        data-toggle="tab"
                                                                                        href="#nav-user" role="tab"
                                                                                        aria-controls="nav-user"
                                                                                        aria-selected="true">User</a>
                                                                                    <a class="nav-item nav-link"
                                                                                        id="nav-approver-tab"
                                                                                        data-toggle="tab"
                                                                                        href="#nav-approver" role="tab"
                                                                                        aria-controls="nav-approver"
                                                                                        aria-selected="false">Approver
                                                                                        DM</a>
                                                                                    <a class="nav-item nav-link"
                                                                                        id="nav-approver-gm-tab"
                                                                                        data-toggle="tab"
                                                                                        href="#nav-approver-gm" role="tab"
                                                                                        aria-controls="nav-approver-gm"
                                                                                        aria-selected="false">Approver
                                                                                        GM</a>
                                                                                    <a class="nav-item nav-link"
                                                                                        id="nav-approver-md-tab"
                                                                                        data-toggle="tab"
                                                                                        href="#nav-approver-md" role="tab"
                                                                                        aria-controls="nav-approver-md"
                                                                                        aria-selected="false">Approver
                                                                                        MD</a>
                                                                                    <a class="nav-item nav-link"
                                                                                        id="nav-aprrover-pu-tab"
                                                                                        data-toggle="tab"
                                                                                        href="#nav-aprrover-pu"
                                                                                        role="tab"
                                                                                        aria-controls="nav-aprrover-pu"
                                                                                        aria-selected="false">Approver
                                                                                        Purchase</a>
                                                                                    <a class="nav-item nav-link"
                                                                                        id="nav-pu-tab"
                                                                                        data-toggle="tab" href="#nav-pu"
                                                                                        role="tab"
                                                                                        aria-controls="nav-pu"
                                                                                        aria-selected="false">Purchase
                                                                                        Staff</a>

                                                                                    <?php }  ?>
                                                                                </div>
                                                                            </nav>
                                                                            <div class="tab-content pl-3 pt-2"
                                                                                id="nav-tabContent">

                                                                                <?php if ($status == '5') { ?>
                                                                                <div class="tab-pane fade "
                                                                                    id="nav-user" role="tabpanel"
                                                                                    aria-labelledby="nav-user-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_user"] ?></textarea>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="nav-approver" role="tabpanel"
                                                                                    aria-labelledby="nav-approver-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_approver_user"] ?></textarea>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="nav-approver-gm" role="tabpanel"
                                                                                    aria-labelledby="nav-approver-gm-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_approver_gm"] ?></textarea>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="nav-approver-md" role="tabpanel"
                                                                                    aria-labelledby="nav-approver-md-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_approver_md"] ?></textarea>
                                                                                </div>
                                                                                <div class="tab-pane fade show active"
                                                                                    id="nav-aprrover-pu" role="tabpanel"
                                                                                    aria-labelledby="nav-aprrover-pu-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_approver_pu"] ?></textarea>
                                                                                </div>
                                                                                <div class="tab-pane fade" id="nav-pu"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="nav-pu-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_pu"] ?></textarea>
                                                                                </div>

                                                                                <?php } else { ?>

                                                                                <div class="tab-pane fade show active"
                                                                                    id="nav-user" role="tabpanel"
                                                                                    aria-labelledby="nav-user-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_user"] ?></textarea>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="nav-approver" role="tabpanel"
                                                                                    aria-labelledby="nav-approver-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_approver_user"] ?></textarea>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="nav-approver-gm" role="tabpanel"
                                                                                    aria-labelledby="nav-approver-gm-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_approver_gm"] ?></textarea>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="nav-approver-md" role="tabpanel"
                                                                                    aria-labelledby="nav-approver-md-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_approver_md"] ?></textarea>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="nav-aprrover-pu" role="tabpanel"
                                                                                    aria-labelledby="nav-aprrover-pu-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_approver_pu"] ?></textarea>
                                                                                </div>
                                                                                <div class="tab-pane fade" id="nav-pu"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="nav-pu-tab">
                                                                                    <textarea class="form-control"
                                                                                        value="" rows="3"
                                                                                        readonly><?php echo $rowT["comment_pu"] ?></textarea>
                                                                                </div>

                                                                                <?php }  ?>

                                                                                <?php
                                                                                $comment_user = $rowT["comment_user"];
                                                                                $comment_pu = $rowT["comment_pu"];
                                                                                $comment_approver_user = $rowT["comment_approver_user"];
                                                                                $comment_approver_gm = $rowT["comment_approver_gm"];
                                                                                $comment_approver_md = $rowT["comment_approver_md"];
                                                                                $comment_approver_pu = $rowT["comment_approver_pu"];
                                                                                $num_req = $rowT["num_req"];
                                                                                $name_request   = $rowT["name_request"];
                                                                                $email = $rowT["email"];
                                                                                $department = $rowT["department"];
                                                                                $tel = $rowT["tel"];

                                                                                // $date_picker = date("d/m/Y", strtotime($rowT['date_picker']->format('Y-m-d')));
                                                                                $status_name = $rowT["status_name"];

                                                                                ?>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </form>
                                                    <?php
                                                    $status = $rowT['status'];
                                                    $approver_user_code =   $rowT['approver_user_code'];
                                                    $approver_user_nameTH =   $rowT['approver_user_nameTH'];
                                                    $approver_gm_code =   $rowT['approver_gm_code'];
                                                    $approver_gm_nameTH =   $rowT['approver_gm_nameTH'];
                                                    $approver_md_code =   $rowT['approver_md_code'];
                                                    $approver_md_nameTH =   $rowT['approver_md_nameTH'];
                                                    $approver_pu_code =   $rowT['approver_pu_code'];
                                                    $approver_pu_nameTH =   $rowT['approver_pu_nameTH'];
                                                    $pu_code =   $rowT['pu_code'];
                                                    $pu_nameTH =   $rowT['pu_nameTH'];
                                                    $work_process_status_approvepu =  $rowT['work_process_status_approvepu'];

                                                    $work_process_dm = $rowT['work_process_status_user'];
                                                    $work_process_gm = $rowT['work_process_status_gm'];
                                                    $work_process_md = $rowT['work_process_status_md'];
                                                    $cal_func = "";
                                                    $comment_app_dm_gm_md = "";
                                                    if($status == 1){
                                                        $cal_func = "db_quatation_edit_approve_dm.php";
                                                        $comment_app_dm_gm_md = $comment_approver_user;
                                                    }else if($status == 2){
                                                        $cal_func = "db_quatation_edit_approve_gm.php";
                                                        $comment_app_dm_gm_md = $comment_approver_gm;
                                                    }else if($status == 9){
                                                        $cal_func = "db_quatation_edit_approve_md.php";
                                                        $comment_app_dm_gm_md = $comment_approver_md;
                                                    }

                                                    $rfq_type = $rowT['tse_rfq_type'];

                                                } while ($rowT = sqlsrv_fetch_array($returnedValueT, SQLSRV_FETCH_ASSOC));
                                                }   ?>
                                                    <?php
                                          $EmployeeCode = $resultT['EmployeeCode'];
                                          $ThFullName = $resultT['ThFullName'];
										
                                       } while ($resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC));
                                    } ?>

                                    <?php
                                    //add by 04404   
                                        $CurrentDMGMMDApproverCode = "";
                                        if($work_process_dm == "pending" || $work_process_dm == "unsuccess"){
                                            $CurrentDMGMMDApproverCode = $approver_user_code;
                                        }else if($work_process_gm == "pending" || $work_process_gm == "unsuccess"){
                                            $CurrentDMGMMDApproverCode = $approver_gm_code;
                                        }else if($work_process_md == "pending" || $work_process_md == "unsuccess"){
                                            $CurrentDMGMMDApproverCode = $approver_md_code;
                                        }
                                    ?>

                                    <!-- Purchase Manager อนุมัติ, ส่งเมล์ให้จัดซื้อ Purchase Staff หาราคา, by 04404 -->
                                    <?php
                                    if ($status == 3 && $EmployeeCode == $pu_code) {
                                        // if ($status == 3 && $EmployeeCode == '1100528') {
                                        $output = 'status == 3 Purchase ';
                                        // echo "<script>console.log('User : " . $output . "' );</script>";
                                        // echo "<script>console.log('Status now : " . $status . "' );</script>";
                                    ?>
                                    <div class="container-fluid" style="padding: 0px;">
                                        <div class="card">
                                            <div class="card-header"><strong class="card-title"><i
                                                        class="fa fa-file-text" aria-hidden="true"
                                                        style="margin-right: 10px;"></i>แก้ไขข้อมูลสินค้า</i>
                                            </div>
                                            <div class="card-body">
                                                <!-- <table class="table"> -->
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:60px">Product name</th>
                                                                <th style="min-width:50px">Quantity</th>
                                                                <th style="min-width:50px">Unit</th>
                                                                <th
                                                                    style="background-color: #28a745;color:white">
                                                                    Price</th>
                                                                <th style="min-width:50px">Save</th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        $summary = 0;
                                                        $queryB = "SELECT quatation.*,request_product.* FROM quatation LEFT JOIN request_product ON quatation.num_req = request_product.num_req WHERE quatation_ID = '$quatation_ID' ";
                                                        $returnedB = sqlsrv_query($conn, $queryB);
                                                        $rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC);

                                                        if ($rowB === false) {
                                                            die(print_r(sqlsrv_errors(), true));
                                                        } elseif ($rowB === null) {
                                                            echo "No results were found.\n";
                                                        } else {
                                                            do {
                                                                $summary = $summary + ($rowB['price'] * $rowB['amount']);
                                                                $sum = ($rowB['price'] * $rowB['amount']);
                                                                // echo "<script>console.log('summary :  (" . $rowB['price'] . " * " . $rowB['amount'] . ") = " . $sum  . "' );</script>";
                                                        ?>

                                                        <form
                                                        action="db_quatation_edit_request_product.php"
                                                        name="save" id="save" method="post"
                                                        ENCTYPE="multipart/form-data">

                                                        <tbody style="font-size: 0.8em;">
                                                            <td><input style="width:300px"
                                                                    type="text" id="product"
                                                                    name="product"
                                                                    value="<?php echo $rowB['product']; ?>"
                                                                    autocomplete="off" readonly>
                                                            </td>
                                                            <td><input style="width:50px"
                                                                    type="text" id="amount"
                                                                    name="amount"
                                                                    value="<?php echo $rowB['amount']; ?>"
                                                                    autocomplete="off" readonly>
                                                            </td>
                                                            <td><input style="width:50px"
                                                                    type="text" id="unit"
                                                                    name="unit"
                                                                    value="<?php echo $rowB['unit']; ?>"
                                                                    autocomplete="off" readonly>
                                                            </td>
                                                            <td style="background-color: #28a745;">
                                                                <input style="width:50px"
                                                                    type="text" id="price"
                                                                    name="price"
                                                                    value="<?php echo $rowB['price']; ?>"
                                                                    autocomplete="off" required>
                                                            </td>
                                                            <td hidden="on"><input type="text"
                                                                    id="request_ID"
                                                                    name="request_ID"
                                                                    value="<?php echo $rowB['request_ID']; ?>"
                                                                    autocomplete="off"></td>
                                                            <td hidden="on"><input type="text"
                                                                    id="quatation_ID"
                                                                    name="quatation_ID"
                                                                    value="<?php echo $quatation_ID ?>"
                                                                    autocomplete="off"></td>

                                                            <td><button style="width:50px"
                                                                    type="submit"
                                                                    onclick="return confirm('ยืนยันการบันทึก');"
                                                                    class="btn btn-success btn-xs"><i
                                                                        class="fa fa-floppy-o"
                                                                        aria-hidden="true"></i>
                                                                </button>

                                                            </td>
                                                        </tbody>
                                                        </form>
                                                        <?php
                                                            } while ($rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC));
                                                        }
                                                        // echo "<script>console.log('summary all :  " . $summary . "' );</script>";
                                                        ?>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="4"
                                                                    style="text-align: right;"><span
                                                                        class="btn btn-outline-info btn-block">
                                                                        Total (บาท) :
                                                                        <?php echo number_format($summary, 0, '', ',');  ?></span>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="db_quatation_edit_pu.php" name="save_pu"
                                            id="save_pu" method="post" ENCTYPE="multipart/form-data">

                                            <input id="quatation_ID" name="quatation_ID" type="text"
                                                value="<?php echo  $quatation_ID ?>" hidden>
                                            <input id="num_req" name="num_req" type="text"
                                                value="<?php echo  $num_req ?>" hidden>
                                            <input id="name_request" name="name_request" type="text"
                                                value="<?php echo  $name_request ?>" hidden>
                                            <input id="email" name="email" type="text"
                                                value="<?php echo  $email ?>" hidden>
                                            <input id="department" name="department" type="text"
                                                value="<?php echo  $department ?>" hidden>
                                            <input id="tel" name="tel" type="text"
                                                value="<?php echo  $tel ?>" hidden>
                                            <input id="date_picker" name="date_picker" type="text"
                                                value="<?php echo $date_picker ?>" hidden>
                                            <input id="status_name" name="status_name" type="text"
                                                value="<?php echo $status_name ?>" hidden>
                                            <input id="comment_pu_last" name="comment_pu_last"
                                                type="text" value="<?php echo  $comment_pu ?>" hidden>
                                            <input id="EmployeeName" name="EmployeeName" type="text"
                                                value="<?php echo  $EmployeeThFirstName . ' ' . $EmployeeThLastName ?>"
                                                hidden>

                                            <?php $comment_pu_last_for_change = $comment_pu; ?>

                                            <div class="row">
                                                <div class="col-12" style=" margin-bottom: 10px;">
                                                    <!-- <label for="input-id"><i class="fa fa-plus" aria-hidden="true"></i> Upload files (Multiple) <label style="color: red;">* .png .jpg .jpeg .pdf .zip .rar .7z (<= 30MB)</label></label> -->
                                                    <label for="input-id"><i class="fa fa-plus"
                                                            aria-hidden="true"></i> Upload files
                                                        (Multiple) <label style="color: red;">(<= 30MB
                                                                per file
                                                                ชื่อไฟล์ต้องเป็นภาษาอังกฤษและตัวเลข 0-9
                                                                เท่านั้น)</label></label>
                                                        <div class="custom-file" id="custom-file">
                                                            <input type="file" style="height: auto;"
                                                                class="custom-file-input" name="files[]"
                                                                id="files" multiple>
                                                            <label class="custom-file-label"
                                                                id="custom-file-label"
                                                                style="height: auto;"
                                                                for="inputGroupFile02">Choose
                                                                file</label>
                                                        </div>
                                                </div>
                                            </div>

                                            <!-- //* javascript ไว้สำหรับเด้งแจ้งเตือนให้อัพโหลดไฟล์เฉพาะ png jpg pdf เท่านั้น -->
                                            <script type="text/javascript">
                                            $('.custom-file input').change(function(e) {
                                                var files = [];
                                                // var allowedExtensions =
                                                //    /(\.jpg|\.jpeg|\.png|\.pdf|\.zip|\.rar|\.7z)$/i;

                                                for (var i = 0; i < $(this)[0].files
                                                    .length; i++) {
                                                    files.push($(this)[0].files[i].name);

                                                    const fileSize = $(this)[0].files[i].size /
                                                        1024 / 1024; // in MiB

                                                    // if (!allowedExtensions.exec($(this)[0].files[i].name)) {
                                                    //    alert('Invalid file type , Please use (.pdf/.jpg/.jpeg/.png) only.');
                                                    //    files = [];
                                                    //    $(this).next('.custom-file-label').html('Choose file');
                                                    //    document.getElementById("tableFile1").style.marginTop = "0px";
                                                    //    return false;
                                                    // } else {
                                                    if (fileSize > 30) {
                                                        alert('File size exceeds 30 MiB');
                                                        files = [];
                                                        $(this).next('.custom-file-label').html(
                                                            'Choose file');
                                                        document.getElementById("tableFile1")
                                                            .style.marginTop = "0px";
                                                        return false;
                                                        // $(file).val(''); //for clearing with Jquery
                                                    }
                                                    // }

                                                    var english =
                                                        /^([a-zA-Z0-9-_()\s]+)\.(?!\.)([a-zA-Z0-9-_()\s]{1,5})(?<!\.)$/;
                                                    if (english.test($(this)[0].files[i]
                                                            .name)) {

                                                        console.log($(this)[0].files[i].name);

                                                    } else {
                                                        alert(
                                                            'ชื่อไฟล์ต้องเป็นภาษาอังกฤษและตัวเลข 0-9 เท่านั้น'
                                                        );
                                                        files = [];
                                                        $(this).next('.custom-file-label').html(
                                                            'Choose file');
                                                        document.getElementById("tableFile1")
                                                            .style.marginTop = "0px";
                                                        return false;
                                                    }
                                                }

                                                $(this).next('.custom-file-label').html(files
                                                    .join('<br> '));
                                                var clientHeightF = document.getElementById(
                                                    "custom-file-label").clientHeight;
                                                var clientHeight = clientHeightF - 10

                                                var h = clientHeight + 'px';
                                                document.getElementById("tableFile1").style
                                                    .marginTop = h;
                                            });
                                            </script>
                                            <!-- //* javascript ไว้สำหรับเด้งแจ้งเตือนให้อัพโหลดไฟล์เฉพาะ png jpg pdf เท่านั้น -->

                                            <!-- tbl: show file upload -->
                                            <div class="row" id="tableFile1">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead class="thead-dark"
                                                                style="font-size: 0.9em;">
                                                                <tr>
                                                                    <th>File name</th>
                                                                    <th>UpdateBy</th>
                                                                    <th>DateCreate</th>
                                                                    <th>DateModified</th>
                                                                    <th>Download</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size: small;">
                                                                <?php
                                                                $query1 = "SELECT * FROM quatation_file WHERE Num_req = '$num_req'";
                                                                $returnedValue1 = sqlsrv_query($conn, $query1);
                                                                $row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC);

                                                                if ($row1 === false) {
                                                                    // echo "Error while fetching array.\n";
                                                                    die(print_r(sqlsrv_errors(), true));
                                                                } else if ($row1 === null) {
                                                                    echo "No results were found.\n";
                                                                } else {
                                                                    do {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $row1["File_name"]; ?>
                                                                    </td>
                                                                    <td><?php echo $row1["Update_by"]; ?>
                                                                    </td>
                                                                    <td><?php echo date_format($row1["Date_create"], "d/m/Y H:i:s"); ?>
                                                                    </td>
                                                                    <td><?php echo date_format($row1["Date_modified"], "d/m/Y H:i:s"); ?>
                                                                    </td>
                                                                    <td>
                                                                        <div
                                                                            class="d-flex justify-content-sm-between">

                                                                            <div class="p-1"><a
                                                                                    href="upload/<?php echo $row1["File_name"]; ?>"
                                                                                    class="btn btn-primary"
                                                                                    download><i
                                                                                        class="fa fa-download"></i></a>
                                                                            </div>
                                                                            <?php
                                                                        $exts = array('gif', 'png', 'jpg', 'pdf', 'jpeg', 'jfif');

                                                                        if (in_array(strtolower(end(explode('.', $row1["File_name"]))), $exts)) {
                                                                        ?>
                                                                            <div class="p-1"><a
                                                                                    href="readFile.php?file=upload/<?php echo $row1["File_name"] ?>"
                                                                                    class="btn btn-success"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            </div>
                                                                            <?php }   ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                    } while ($row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC));
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Comment -->
                                            <div class="row" id="commentPu">
                                                <div class="form-group col-md-12 col-sm-12">
                                                    <label for="comment_pu"><i
                                                            class="fa fa-commenting-o"
                                                            aria-hidden="true"></i> Comment from
                                                        Purchase</label>
                                                    <textarea class="form-control"
                                                        placeholder="Please , Enter a comment . . ."
                                                        rows="3" name="comment_pu"
                                                        id="comment_pu_java"></textarea>
                                                </div>
                                            </div>

                                            <!-- Button-summit-form -->
                                            <div class="row ">
                                                <div class="col-md-2 col-sm-12"> <a href="Dashboard.php"
                                                        class="btn btn-block btn-secondary"><i
                                                            class="fa fa-arrow-left"
                                                            aria-hidden="true"></i>
                                                        Back</a></div>
                                                <div class="col-md-1 col-sm-12"> </div>
                                                <div class="col-md-3 col-sm-12 ">
                                                    <button type="submit"
                                                        onclick="return confirm('ยืนยันการบันทึก');"
                                                        value="submit" name="submit"
                                                        class="btn btn-block btn-success"><i
                                                            class="fa fa-check" aria-hidden="true"></i>
                                                        Submit</button>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit"
                                                        onclick="return confirm('ยืนยันการบันทึก');"
                                                        value="cancel" name="submit"
                                                        class="btn btn-block btn-danger"><i
                                                            class="fa fa-times" aria-hidden="true"></i>
                                                        Cancel</button>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit" value="change" name="submit"
                                                        onClick="return confirm('Are you sure you want to change staff?') && (function(){                                                                                                    
                                                                                                $('#datepicker1').attr('disabled', true);
                                                                                                $('#pu_code_edit').attr('disabled', true);
                                                                                                })();
                                                                                                "
                                                        class="btn btn-block btn-secondary"><i
                                                            class="fa fa-refresh"
                                                            aria-hidden="true"></i> <i
                                                            class="fa fa-user" aria-hidden="true"></i>
                                                        Change</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- DM, GM, MD อนุมัติครบตามลำดับอนุมัติ, แล้วส่งเมล์ให้ Purchase Manager อนุมัติ, by 04404 -->
                                    <?php 
                                    } elseif (($status == 2 || $status == 9 || $status == 10) && ($work_process_dm == "success" || $work_process_dm == "skip") && ($work_process_gm == "success" || $work_process_gm == "skip") && ($work_process_md == "success" || $work_process_md == "skip") && $EmployeeCode == $approver_pu_code) {
                                        // 
                                        // } elseif ($status == 2 && $EmployeeCode == '1100528') {
                                        // 
                                        $output = 'status == 2 Approver Purchase ';
                                        // echo "<script>console.log('User : " . $output . "' );</script>";
                                        // echo "<script>console.log('Status now : " . $status . "' );</script>";
                                    ?>
                                    <div class="container-fluid" style="padding: 0px;">
                                        <div class="card">
                                            <div class="card-header"><strong class="card-title"><i
                                                        class="fa fa-file-text" aria-hidden="true"
                                                        style="margin-right: 10px;"></i>แก้ไขข้อมูลสินค้า</i>
                                            </div>
                                            <div class="card-body">
                                                <!-- <table class="table"> -->
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="min-width:300px">Product name</th>
                                                                <th style="min-width:50px">Quantity</th>
                                                                <th style="min-width:50px">Unit</th>
                                                                <th style="min-width:50px">Price</th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        $summary = 0;
                                                        $queryB = "SELECT quatation.*,request_product.* FROM quatation LEFT JOIN request_product ON quatation.num_req = request_product.num_req WHERE quatation_ID = '$quatation_ID' ";
                                                        $returnedB = sqlsrv_query($conn, $queryB);
                                                        $rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC);
                                                        if ($rowB === false) {
                                                            die(print_r(sqlsrv_errors(), true));
                                                        } elseif ($rowB === null) {
                                                            echo "No results were found.\n";
                                                        } else {
                                                            do {
                                                                $summary = $summary + ($rowB['price'] * $rowB['amount']);
                                                                $sum = ($rowB['price'] * $rowB['amount']);
                                                                // echo "<script>console.log('summary :  (" . $rowB['price'] . " * " . $rowB['amount'] . ") = " . $sum  . "' );</script>";
                                                        ?>
                                                        <form
                                                        action="db_quatation_edit_request_product.php"
                                                        name="save" id="save" method="post"
                                                        ENCTYPE="multipart/form-data">

                                                        <tbody style="font-size: 0.8em;">
                                                            <td><input style="width:300px"
                                                                    type="text" id="product"
                                                                    name="product"
                                                                    value="<?php echo $rowB['product']; ?>"
                                                                    autocomplete="off" readonly>
                                                            </td>
                                                            <td><input style="width:50px"
                                                                    type="text" id="amount"
                                                                    name="amount"
                                                                    value="<?php echo $rowB['amount']; ?>"
                                                                    autocomplete="off" readonly>
                                                            </td>
                                                            <td><input style="width:50px"
                                                                    type="text" id="unit"
                                                                    name="unit"
                                                                    value="<?php echo $rowB['unit']; ?>"
                                                                    autocomplete="off" readonly>
                                                            </td>
                                                            <td><input style="width:50px"
                                                                    type="text" id="price"
                                                                    name="price"
                                                                    value="<?php echo $rowB['price']; ?>"
                                                                    autocomplete="off" readonly>
                                                            </td>
                                                        </tbody>
                                                        </form>
                                                        <?php
                                                            } while ($rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC));
                                                        }
                                                            // echo "<script>console.log('summary all :  " . $summary . "' );</script>";
                                                        ?>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="4"
                                                                    style="text-align: right;"><span
                                                                        class="btn btn-outline-info btn-block">
                                                                        Total (บาท) :
                                                                        <?php echo number_format($summary, 0, '', ','); ?></span>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="db_quatation_edit_approver_pu.php"
                                            name="save_approver_pu" id="save_approver_pu" method="post"
                                            ENCTYPE="multipart/form-data">

                                            <input id="quatation_ID" name="quatation_ID" type="text"
                                                value="<?php echo  $quatation_ID ?>" hidden>
                                            <input id="num_req" name="num_req" type="text"
                                                value="<?php echo  $num_req ?>" hidden>
                                            <input id="name_request" name="name_request" type="text"
                                                value="<?php echo  $name_request ?>" hidden>
                                            <input id="email" name="email" type="text"
                                                value="<?php echo  $email ?>" hidden>
                                            <input id="department" name="department" type="text"
                                                value="<?php echo  $department ?>" hidden>
                                            <input id="tel" name="tel" type="text"
                                                value="<?php echo  $tel ?>" hidden>
                                            <input id="date_picker" name="date_picker" type="text"
                                                value="<?php echo  $date_picker ?>" hidden>
                                            <input id="status_name" name="status_name" type="text"
                                                value="<?php echo  $status_name ?>" hidden>
                                            <input id="comment_approver_pu_last"
                                                name="comment_approver_pu_last" type="text"
                                                value="<?php echo  $comment_approver_pu ?>" hidden>


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead class="thead-dark"
                                                                style="font-size: 0.9em;">
                                                                <tr>
                                                                    <th>File name</th>
                                                                    <th>UpdateBy</th>
                                                                    <th>DateCreate</th>
                                                                    <th>DateModified</th>
                                                                    <th>Download</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size: small;">
                                                                <?php
                                                                $query1 = "SELECT * FROM quatation_file WHERE Num_req = '$num_req'";
                                                                $returnedValue1 = sqlsrv_query($conn, $query1);
                                                                $row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC);

                                                                if ($row1 === false) {
                                                                    // echo "Error while fetching array.\n";
                                                                    die(print_r(sqlsrv_errors(), true));
                                                                } else if ($row1 === null) {
                                                                    echo "No results were found.\n";
                                                                } else {
                                                                    do {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $row1["File_name"]; ?>
                                                                    </td>
                                                                    <td><?php echo $row1["Update_by"]; ?>
                                                                    </td>
                                                                    <td><?php echo date_format($row1["Date_create"], "d/m/Y H:i:s"); ?>
                                                                    </td>
                                                                    <td><?php echo date_format($row1["Date_modified"], "d/m/Y H:i:s"); ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex justify-content-sm-between">
                                                                            <div class="p-1"><a
                                                                                    href="upload/<?php echo $row1["File_name"]; ?>"
                                                                                    class="btn btn-primary"
                                                                                    download><i
                                                                                        class="fa fa-download"></i></a>
                                                                            </div>
                                                                            <?php
                                                                            $exts = array('gif', 'png', 'jpg', 'pdf', 'jpeg', 'jfif');
                                                                            if (in_array(strtolower(end(explode('.', $row1["File_name"]))), $exts)) {
                                                                            ?>
                                                                            <div class="p-1"><a
                                                                                    href="readFile.php?file=upload/<?php echo $row1["File_name"] ?>"
                                                                                    class="btn btn-success"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            </div>
                                                                            <?php }  ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                } while ($row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC));
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12 col-sm-12">
                                                    <label for="comment_from_pu"><i
                                                            class="fa fa-commenting-o"
                                                            aria-hidden="true"></i> Comment from
                                                        Approver Purchase</label>
                                                    <textarea class="form-control"
                                                        placeholder="Please , Enter a comment . . ."
                                                        rows="3" name="comment_approver_pu"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <strong class="card-title"><label for="date_picker"><i
                                                            class="fa fa-calendar"
                                                            aria-hidden="true"></i> Expected
                                                        date</label></strong>
                                                <div class="input-group">
                                                    <input class="datepicker form-control"
                                                        data-provide="datepicker" id="datepicker1"
                                                        name="datepicker" autocomplete="off" required>
                                                    <div class="input-group-btn">
                                                        <span class="btn btn-secondary"><i
                                                                class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="pu" class="form-select">Purchase
                                                        Staff</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text"
                                                                for="pu_code">ผู้จัดซื้อ</label>
                                                        </div>
                                                        <select class="custom-select" id="pu_code_edit"
                                                            name="pu_code" required>
                                                            <option value="">..กรุณาเลือกผู้จัดซื้อ..
                                                            </option>
                                                                <?php
                                                                $sqlPurchaseDivision = "SELECT * FROM vw_Employee where EmployeeCode = '$EmployeeCode'";
                                                                $queryPurchaseDivision = sqlsrv_query($conn, $sqlPurchaseDivision);
                                                                $resultPurchaseDivision = sqlsrv_fetch_array($queryPurchaseDivision, SQLSRV_FETCH_ASSOC);
                                                                $DivisionCode = $resultPurchaseDivision['DivisionCode'];
                                                                $sqlPurchase = "SELECT * FROM admin_purchase where Type = 'Staff'";
                                                                $queryPurchase = sqlsrv_query($conn, $sqlPurchase);
                                                                $resultPurchase = sqlsrv_fetch_array($queryPurchase, SQLSRV_FETCH_ASSOC);
                                                                if (!$resultPurchase) {
                                                                    // echo "Error while fetching array.\n";
                                                                    die(print_r(sqlsrv_errors(), true));
                                                                } else if ($resultPurchase === null) {
                                                                    echo "No results were found.\n";
                                                                } else {
                                                                    do {
                                                                ?>

                                                            <option
                                                                value="<?php echo $resultPurchase["EmployeeCode"]; ?>">
                                                                <?php echo $resultPurchase["ThFullName"]; ?>
                                                            </option>
                                                            <?php
                                                            } while ($resultPurchase = sqlsrv_fetch_array($queryPurchase, SQLSRV_FETCH_ASSOC));
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-2 col-sm-12"> <a href="Dashboard.php"
                                                        class="btn btn-block btn-secondary"><i
                                                            class="fa fa-arrow-left"
                                                            aria-hidden="true"></i>
                                                        Back</a></div>
                                                <div class="col-md-4 col-sm-12"> </div>

                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit" value="submit" name="submit"
                                                        onclick=" return confirm('ยืนยันการบันทึก')"
                                                        class="btn btn-block btn-success"><i
                                                            class="fa fa-check" aria-hidden="true"></i>
                                                        Submit2222</button>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit" value="cancel" name="submit"
                                                        onClick="return confirm('Are you sure you want to delete?') && (function(){    
                                                                                                
                                                                                                $('#datepicker1').attr('disabled', true);
                                                                                                $('#pu_code_edit').attr('disabled', true);
                                                                                                })();
                                                                                                "
                                                        class="btn btn-block btn-danger"><i
                                                            class="fa fa-times" aria-hidden="true"></i>
                                                        Cancel2222</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <input id="quatation_ID" name="quatation_ID" type="text"
                                        value="<?php echo  $quatation_ID ?>" hidden>
                                    <input id="num_req" name="num_req" type="text"
                                        value="<?php echo  $num_req ?>" hidden>
                                    <input id="name_request" name="name_request" type="text"
                                        value="<?php echo  $name_request ?>" hidden>
                                    <input id="email" name="email" type="text"
                                        value="<?php echo  $email ?>" hidden>
                                    <input id="department" name="department" type="text"
                                        value="<?php echo  $department ?>" hidden>
                                    <input id="tel" name="tel" type="text" value="<?php echo  $tel ?>"
                                        hidden>
                                    <input id="date_picker" name="date_picker" type="text"
                                        value="<?php echo  $date_picker ?>" hidden>
                                    <input id="status_name" name="status_name" type="text"
                                        value="<?php echo  $status_name ?>" hidden>
                                    <input id="comment_approver_pu_last" name="comment_approver_pu_last"
                                        type="text" value="<?php echo  $comment_approver_pu ?>" hidden>

                                    <!-- User สร้างเอกสาร, ส่งเมล์ให้ Department Manager อนุมัติ, by 04404 -->
                                    <?php
                                    } elseif (($status == 1 || $status == 2 || $status == 9) && $EmployeeCode == $CurrentDMGMMDApproverCode) {
                                    // } elseif ($status == 1 && $EmployeeCode == $approver_user_code) {
                                    $output = 'status == 1 Approver User ';
                                        // echo "<script>console.log('User : " . $output . "' );</script>";
                                        // echo "<script>console.log('Status now : " . $status . "' );</script>";
                                    ?>
                                    <div class="container-fluid" style="padding: 0px;">
                                        
                                        <div class="card">
                                            <div class="card-header"><strong class="card-title"><i
                                                        class="fa fa-file-text" aria-hidden="true"
                                                        style="margin-right: 10px;"></i>ข้อมูลสินค้า</i>
                                            </div>
                                            <div class="card-body">
                                                <!-- <table class="table"> -->
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="min-width:300px">Product name</th>
                                                                <th style="min-width:50px">Quantity</th>
                                                                <th style="min-width:50px">Unit</th>
                                                                <th style="min-width:50px">Price</th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        $summary = 0;
                                                        $queryB = "SELECT quatation.*,request_product.* FROM quatation LEFT JOIN request_product ON quatation.num_req = request_product.num_req WHERE quatation_ID = '$quatation_ID' ";
                                                        $returnedB = sqlsrv_query($conn, $queryB);
                                                        $rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC);

                                                        if ($rowB === false) {
                                                            die(print_r(sqlsrv_errors(), true));
                                                        } elseif ($rowB === null) {
                                                            echo "No results were found.\n";
                                                        } else {
                                                            do {
                                                                $summary = $summary + ($rowB['price'] * $rowB['amount']);
                                                                $sum = ($rowB['price'] * $rowB['amount']);
                                                                // echo "<script>console.log('summary :  (" . $rowB['price'] . " * " . $rowB['amount'] . ") = " . $sum  . "' );</script>";
                                                        ?>
                                                        <tbody style="font-size: 0.8em;">
                                                            <td><input style="width:300px" type="text"
                                                                    id="product" name="product"
                                                                    value="<?php echo $rowB['product']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                            <td><input style="width:50px" type="text"
                                                                    id="amount" name="amount"
                                                                    value="<?php echo $rowB['amount']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                            <td><input style="width:50px"
                                                                    style="width:50px" type="text"
                                                                    id="unit" name="unit"
                                                                    value="<?php echo $rowB['unit']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                            <td><input style="width:50px"
                                                                    style="width:50px" type="text"
                                                                    id="price" name="price"
                                                                    value="<?php echo $rowB['price']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                        </tbody>
                                                        </form>
                                                        <?php

                                                            } while ($rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC));
                                                        }
                                                        // echo "<script>console.log('summary all :  " . $summary . "' );</script>";
                                                        ?>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="4" style="text-align: right;"><span
                                                                        class="btn btn-outline-info btn-block"> Total (บาท) :
                                                                        <?php echo number_format($summary, 0, '', ','); ?></span>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="<?=$cal_func?>" name="save_approver_user"
                                            id="save_approver_user" method="post" ENCTYPE="multipart/form-data">
                                            <input id="quatation_ID" name="quatation_ID" type="text"
                                                value="<?php echo  $quatation_ID ?>" hidden>
                                            <input id="num_req" name="num_req" type="text" value="<?php echo  $num_req ?>"
                                                hidden>
                                            <input id="name_request" name="name_request" type="text"
                                                value="<?php echo  $name_request ?>" hidden>
                                            <input id="email" name="email" type="text" value="<?php echo  $email ?>" hidden>
                                            <input id="department" name="department" type="text"
                                                value="<?php echo  $department ?>" hidden>
                                            <input id="tel" name="tel" type="text" value="<?php echo  $tel ?>" hidden>
                                            <input id="date_picker" name="date_picker" type="text"
                                                value="<?php echo  $date_picker ?>" hidden>
                                            <input id="status_name" name="status_name" type="text"
                                                value="<?php echo  $status_name ?>" hidden>
                                            <input id="comment_approver_user_last" name="comment_approver_user_last"
                                                type="text" value="<?php echo  $comment_app_dm_gm_md ?>" hidden>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead class="thead-dark" style="font-size: 0.9em;">
                                                                <tr>
                                                                    <th>File name</th>
                                                                    <th>UpdateBy</th>
                                                                    <th>DateCreate</th>
                                                                    <th>DateModified</th>
                                                                    <th>Download</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size: small;">
                                                                <?php
                                                                $query1 = "SELECT * FROM quatation_file WHERE Num_req = '$num_req'";
                                                                $returnedValue1 = sqlsrv_query($conn, $query1);
                                                                $row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC);

                                                                if ($row1 === false) {
                                                                    // echo "Error while fetching array.\n";
                                                                    die(print_r(sqlsrv_errors(), true));
                                                                } else if ($row1 === null) {
                                                                    echo "No results were found.\n";
                                                                } else {
                                                                    do {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $row1["File_name"]; ?></td>
                                                                    <td><?php echo $row1["Update_by"]; ?></td>
                                                                    <td><?php echo date_format($row1["Date_create"], "d/m/Y H:i:s"); ?>
                                                                    </td>
                                                                    <td><?php echo date_format($row1["Date_modified"], "d/m/Y H:i:s"); ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex justify-content-sm-between">
                                                                            <div class="p-1"><a
                                                                                href="upload/<?php echo $row1["File_name"]; ?>"
                                                                                class="btn btn-primary" download><i
                                                                                    class="fa fa-download"></i></a>
                                                                            </div>
                                                                            <?php
                                                                            $exts = array('gif', 'png', 'jpg', 'pdf', 'jpeg', 'jfif');
                                                                            if (in_array(strtolower(end(explode('.', $row1["File_name"]))), $exts)) {
                                                                            ?>

                                                                            <div class="p-1"><a
                                                                                    href="readFile.php?file=upload/<?php echo $row1["File_name"] ?>"
                                                                                    class="btn btn-success"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                    } while ($row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC));
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                             <!-- ======================================04726 option============================ -->
                                                <div class="row">
                                                    <div class="col-12">

                                                        <strong>
                                                            <label class="form-label mb-1">
                                                                <i class="fa fa-file-text-o"></i> Purchase Manage
                                                            </label>
                                                        </strong>

                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">Purchase</span>

                                                            <!-- ใช้ตัวนี้ตัวเดียว -->
                                                            <select class="custom-select" name="pu_code" required>
                                                                <option value="" disabled selected>
                                                                    Please select a Purchase...
                                                                </option>

                                                                <?php
                                                                include("connect.php");

                                                                $sql = "
                                                                    SELECT [EmployeeCode]
                                                                    ,[ThFullName]
                                                                    ,[PlantNameTH]
                                                                    ,[SubLevelNo]
                                                                    ,[Position]
                                                                    ,[Type]
                                                                FROM [E-Form_Purchase_new_Qo].[dbo].[admin_purchase] 
                                                                WHERE [Type] NOT LIKE 'Admin' AND [Type] NOT LIKE 'Approver'
                                                                ";

                                                                $query = sqlsrv_query($conn, $sql);
                                                                $no = 1;

                                                                while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                                                                ?>
                                                                    <option value="<?= $row['EmployeeCode']; ?>">
                                                                        <?= $no . '. ' . $row['ThFullName']; ?>
                                                                    </option>
                                                                <?php 
                                                                    $no++; 
                                                                } 
                                                                ?>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- ================================================================== -->
                                            <div class="row">
                                                <div class="form-group col-md-12 col-sm-12">
                                                    <label for="comment_approver_user"><i class="fa fa-commenting-o"
                                                            aria-hidden="true"></i> Comment</label>
                                                    <textarea class="form-control"
                                                        placeholder="Please , Enter a comment . . ." rows="3"
                                                        name="comment_approver_user"></textarea>
                                                </div>
                                            </div>
                                            <?php
                                                $sql_next = "SELECT Top 1 quatation.*,status.*,RFQType.Id, RFQType.RFQ_Type FROM quatation LEFT JOIN status ON quatation.status = status.status_id Left Join RFQType On quatation.tse_rfq_type = RFQType.Id WHERE quatation_ID = '$quatation_ID' "; // 20220203 add by 04404
                                                $query_next = sqlsrv_query($conn, $sql_next);
                                                $result_next = sqlsrv_fetch_array($query_next, SQLSRV_FETCH_ASSOC);
                                                if (!$result_next) {
                                                    echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                    echo "<script>window.location.href='Dashboard.php';</script>";
                                                    exit;
                                                } else if ($result_next === null) {
                                                    echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                    echo "<script>window.location.href='Dashboard.php';</script>";
                                                    exit;
                                                } else {
                                                    do {
                                                        $work_gm = $result_next["work_process_status_gm"];
                                                        $work_md = $result_next["work_process_status_md"];
                                                    } while ($result_next = sqlsrv_fetch_array($query_next, SQLSRV_FETCH_ASSOC));
                                                }

                                                if($work_gm == 'wait' || $work_gm == 'unsuccess'){
                                                    $sql_ = "SELECT Top 1 app.* FROM ApproveLevelVw AS app INNER JOIN
                                                                    vw_Employee AS emp ON app.Plant = emp.PlantCode AND app.Dept = emp.DepartmentCode INNER JOIN
                                                                    quatation AS qua ON emp.EmployeeCode = qua.employee_code_request
                                                                    WHERE (qua.quatation_ID = '$quatation_ID') AND (app.Lv = '2') ";
                                                    $query_ = sqlsrv_query($conn, $sql_);
                                                    $result_ = sqlsrv_fetch_array($query_, SQLSRV_FETCH_ASSOC);
                                                    if (!$result_) {
                                                        echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                        echo "<script>window.location.href='Dashboard.php';</script>";
                                                        exit;
                                                    } else if ($result_ === null) {
                                                        echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                        echo "<script>window.location.href='Dashboard.php';</script>";
                                                        exit;
                                                    } else {
                                                        $approver_code = $result_["Emp_Num"];
                                                        $approver_nameTH = $result_["ThFullName"];
                                                        $approver_label = 'General Manager';
                                                    }
                                                }else if($work_md == 'wait' || $work_md == 'unsuccess'){
                                                    $sql_ = "SELECT app.* FROM ApproveLevelVw AS app INNER JOIN
                                                                    vw_Employee AS emp ON app.Plant = emp.PlantCode AND app.Dept = emp.DepartmentCode INNER JOIN
                                                                    quatation AS qua ON emp.EmployeeCode = qua.employee_code_request
                                                                    WHERE (qua.quatation_ID = '$quatation_ID') AND (app.Lv = '3') ";
                                                    $query_ = sqlsrv_query($conn, $sql_);
                                                    $result_ = sqlsrv_fetch_array($query_, SQLSRV_FETCH_ASSOC);
                                                    if (!$result_) {
                                                        echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                        echo "<script>window.location.href='Dashboard.php';</script>";
                                                        exit;
                                                    } else if ($result_ === null) {
                                                        echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                        echo "<script>window.location.href='Dashboard.php';</script>";
                                                        exit;
                                                    } else {
                                                        $approver_code = $result_["Emp_Num"];
                                                        $approver_nameTH = $result_["ThFullName"];
                                                        $approver_label = 'Managing Director';
                                                    }
                                                }else{
                                                    $sql_ = "SELECT Top 1 * FROM admin_purchase WHERE (Type = 'Approver') Order By EmployeeCode Desc ";
                                                    $query_ = sqlsrv_query($conn, $sql_);
                                                    $result_ = sqlsrv_fetch_array($query_, SQLSRV_FETCH_ASSOC);
                                                    if (!$result_) {
                                                        echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                        echo "<script>window.location.href='Dashboard.php';</script>";
                                                        exit;
                                                    } else if ($result_ === null) {
                                                        echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                        echo "<script>window.location.href='Dashboard.php';</script>";
                                                        exit;
                                                    } else {
                                                        $approver_code = $result_["EmployeeCode"];
                                                        $approver_nameTH = $result_["ThFullName"];
                                                        $approver_label = 'Purchase Manager';
                                                    }
                                                }
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">                                                    
                                                    <div class="form-group">
                                                        <strong class="card-title">
                                                            <label for="approver_name" class="control-label mb-1"><?=$approver_label?></label>
                                                        </strong>                                                        
                                                        <input id="approver_name" name="approver_name"
                                                            value="<?php echo $approver_nameTH ?>" type="text"
                                                            class="form-control" aria-required="true"
                                                            aria-invalid="false" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <input type='text' class="form-control" id='approver_code' name='approver_code'
                                                        value='<?=$approver_code?>' style='display: none;'>
                                                </div>
                                            </div>

                                            <!-- <div class="row">
                                                <div class="col-md-12">
                                                    <label for="pu" class="form-select">Manager Purchase</label>
                                                    <div class="input-group mb-3">
                                                        <select class="custom-select" id="approver_pu_code"
                                                            name="approver_pu_code">
                                                            <option value="">Please select Approver purchase . . .</option> -->
                                                            <?php
                                                            // $sqlApproverPurchase = "SELECT * FROM admin_purchase where Type = 'Approver'";
                                                            // $queryApproverPurchase = sqlsrv_query($conn, $sqlApproverPurchase);
                                                            // $resultApproverPurchase = sqlsrv_fetch_array($queryApproverPurchase, SQLSRV_FETCH_ASSOC);
                                                            // if (!$resultApproverPurchase) {
                                                            //     echo '<script language="javascript">';
                                                            //     echo 'alert("ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ ADMIN")';
                                                            //     echo '</script>';
                                                            //     header("Location: Dashboard.php");
                                                            // } else if ($resultApproverPurchase === null) {
                                                            //     echo '<script language="javascript">';
                                                            //     echo 'alert("ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ ADMIN")';
                                                            //     echo '</script>';
                                                            //     header("Location: Dashboard.php");
                                                            // } else {
                                                            //     do { 
                                                                ?>
                                                                <!-- <option
                                                                    value="<?php echo $resultApproverPurchase["EmployeeCode"]; ?>">
                                                                    คุณ <?php echo $resultApproverPurchase["ThFullName"]; ?>
                                                                </option> -->
                                                                <?php
                                                            //         } while ($resultApproverPurchase = sqlsrv_fetch_array($queryApproverPurchase, SQLSRV_FETCH_ASSOC));
                                                            // } 
                                                                ?>
                                                        <!-- </select>
                                                    </div>
                                                </div>
                                            </div> -->

                                            <div class="row">
                                                <div class="col-md-2 col-sm-12"> <a href="Dashboard.php"
                                                        class="btn btn-block btn-secondary"><i class="fa fa-arrow-left"
                                                            aria-hidden="true"></i>
                                                        Back</a></div>
                                                <div class="col-md-4 col-sm-12"> </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit" value="submit" name="submit"
                                                        onclick=" return confirm('ยืนยันการบันทึก')"
                                                        class="btn btn-block btn-success"><i class="fa fa-check"
                                                            aria-hidden="true"></i>
                                                        Submit</button>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit" value="cancel" name="submit"
                                                        onclick=" return confirm('ยืนยันการบันทึก')"
                                                        class="btn btn-block btn-danger"><i class="fa fa-times"
                                                            aria-hidden="true"></i>
                                                        Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Department Manager ไม่อนุมัติ, ส่งเมล์ให้ User Revise เอกสารใหม่, by 04404 -->
                                    <?php
                                    } elseif (($status == 6 || $status == 11 || $status == 12) && $EmployeeCode == $employee_code_request) {
                                        // } elseif ($status == 6 && $EmployeeCode == "1100529") {
                                        $output = 'status == 6 user คือ user หลังจากที่ manager user ไม่ approve ให้  ';
                                        // echo "<script>console.log('User : " . $output . "' );</script>";
                                        // echo "<script>console.log('Status now : " . $status . "' );</script>";
                                    ?>
                                    <div class="container-fluid" style="padding: 0px;">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong class="card-title"><i class="fa fa-file-text"
                                                        aria-hidden="true"
                                                        style="margin-right: 10px;"></i>แก้ไขข้อมูลสินค้า</i>
                                                    </div>
                                            <div class="card-body">
                                                <!-- <table class="table"> -->
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="min-width:300px">Product name</th>
                                                                <th style="min-width:50px">Quantity</th>
                                                                <th style="min-width:50px">Unit</th>
                                                                <th style="min-width:50px">Save</th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        $summary = 0;
                                                        $queryB = "SELECT quatation.*,request_product.* FROM quatation LEFT JOIN request_product ON quatation.num_req = request_product.num_req WHERE quatation_ID = '$quatation_ID' ";
                                                        $returnedB = sqlsrv_query($conn, $queryB);
                                                        $rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC);
                                                        if ($rowB === false) {
                                                            die(print_r(sqlsrv_errors(), true));
                                                        } elseif ($rowB === null) {
                                                            echo "No results were found.\n";
                                                        } else {
                                                            do {
                                                                $summary = $summary + ($rowB['price'] * $rowB['amount']);
                                                                $sum = ($rowB['price'] * $rowB['amount']);
                                                                // echo "<script>console.log('summary :  (" . $rowB['price'] . " * " . $rowB['amount'] . ") = " . $sum  . "' );</script>";
                                                        ?>
                                                        <form action="db_quatation_edit_request_product_user.php"
                                                            name="save" id="save" method="post"
                                                            ENCTYPE="multipart/form-data">
                                                            <tbody style="font-size: 0.8em;">
                                                                <td><input style="width:300px" type="text" id="product"
                                                                        name="product"
                                                                        value="<?php echo $rowB['product']; ?>"
                                                                        autocomplete="off"></td>
                                                                <td><input style="width:50px" type="text" id="amount"
                                                                        name="amount" value="<?php echo $rowB['amount']; ?>"
                                                                        autocomplete="off"></td>
                                                                <td><input style="width:50px" type="text" id="unit"
                                                                        name="unit" value="<?php echo $rowB['unit']; ?>"
                                                                        autocomplete="off"></td>
                                                                <td hidden="on"><input type="text" id="request_ID"
                                                                        name="request_ID"
                                                                        value="<?php echo $rowB['request_ID']; ?>"
                                                                        autocomplete="off"></td>
                                                                <td hidden="on"><input type="text" id="quatation_ID"
                                                                        name="quatation_ID"
                                                                        value="<?php echo $quatation_ID ?>"
                                                                        autocomplete="off"></td>
                                                                <td><button style="width:50px"
                                                                        onclick="return confirm('ยืนยันการบันทึก');"
                                                                        type="submit" class="btn btn-success btn-xs"><i
                                                                            class="fa fa-floppy-o" aria-hidden="true"></i>
                                                                    </button>
                                                                </td>
                                                            </tbody>
                                                        </form>
                                                        <?php
                                                            } while ($rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC));
                                                        }
                                                        // echo "<script>console.log('summary all :  " . $summary . "' );</script>";
                                                        ?>
                                                            <tfoot>
                                                                <tr>
                                                                <th colspan="4" style="text-align: right;"><span
                                                                        class="btn btn-outline-info btn-block"> Total (บาท)
                                                                        :
                                                                        <?php echo number_format($summary, 0, '', ','); ?></span>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <form action="db_quatation_edit_user.php" name="save_user" id="save_user"
                                            method="post" ENCTYPE="multipart/form-data">
                                            <input id="quatation_ID" name="quatation_ID" type="text"
                                                value="<?php echo  $quatation_ID ?>" hidden>
                                            <input id="num_req" name="num_req" type="text" value="<?php echo  $num_req ?>"
                                                hidden>
                                            <input id="name_request" name="name_request" type="text"
                                                value="<?php echo  $name_request ?>" hidden>
                                            <input id="email" name="email" type="text" value="<?php echo  $email ?>" hidden>
                                            <input id="department" name="department" type="text"
                                                value="<?php echo  $department ?>" hidden>
                                            <input id="tel" name="tel" type="text" value="<?php echo  $tel ?>" hidden>
                                            <input id="date_picker" name="date_picker" type="text"
                                                value="<?php echo  $date_picker ?>" hidden>
                                            <input id="status_name" name="status_name" type="text"
                                                value="<?php echo  $status_name ?>" hidden>
                                            <input id="comment_user_last" name="comment_user_last" type="text"
                                                value="<?php echo  $comment_user ?>" hidden>
                                            <input id="rfq_type_edit" name="rfq_type_edit" type="text"
                                                value="<?php echo  $rfq_type ?>" hidden>

                                            <div class="row">
                                                <div class="col-12" style=" margin-bottom: 10px;">
                                                    <!-- <label for="input-id"><i class="fa fa-plus" aria-hidden="true"></i> Upload files (Multiple) <label style="color: red;">* .png .jpg .jpeg .pdf .zip .rar .7z (<= 30MB)</label></label> -->
                                                    <label for="input-id"><i class="fa fa-plus" aria-hidden="true"></i>
                                                    Upload files (Multiple) <label style="color: red;">(<= 30MB per file
                                                            ชื่อไฟล์ต้องเป็นภาษาอังกฤษและตัวเลข 0-9 เท่านั้น)</label>
                                                    </label>
                                                    <div class="custom-file" id="custom-file">
                                                        <input type="file" style="height: auto;"
                                                            class="custom-file-input" name="fileToUpload[]" id="files"
                                                            multiple>
                                                        <label class="custom-file-label" id="custom-file-label"
                                                            style="height: auto;" for="files">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- //* javascript ไว้สำหรับเด้งแจ้งเตือนให้อัพโหลดไฟล์เฉพาะ png jpg pdf เท่านั้น -->
                                            <script type="text/javascript">
                                                $('.custom-file input').change(function(e) {
                                                    var files = [];
                                                    // var allowedExtensions =
                                                    //    /(\.jpg|\.jpeg|\.png|\.pdf|\.zip|\.rar|\.7z)$/i;
                                                    for (var i = 0; i < $(this)[0].files.length; i++) {
                                                        files.push($(this)[0].files[i].name);
                                                        const fileSize = $(this)[0].files[i].size / 1024 /
                                                            1024; // in MiB
                                                        // if (!allowedExtensions.exec($(this)[0].files[i].name)) {
                                                        //    alert('Invalid file type , Please use (.pdf/.jpg/.jpeg/.png) only.');
                                                        //    files = [];
                                                        //    $(this).next('.custom-file-label').html('Choose file');
                                                        //    document.getElementById("tableFile1").style.marginTop = "0px";
                                                        //    return false;
                                                        // } else {
                                                        if (fileSize > 30) {
                                                            alert('File size exceeds 30 MiB');
                                                            files = [];
                                                            $(this).next('.custom-file-label').html('Choose file');
                                                            document.getElementById("tableFile").style.marginTop =
                                                                "0px";
                                                            return false;
                                                            // $(file).val(''); //for clearing with Jquery
                                                        }
                                                        // }
                                                        // if (/^[a-zA-Z0-9 _.()!?"-]+$/.test($(this)[0].files[i].name)) {
                                                        // if (!($(this)[0].files[i].name).match(/^([a-z0-9\_])+$/i)) {
                                                        var english =
                                                            /^([a-zA-Z0-9-_()\s]+)\.(?!\.)([a-zA-Z0-9-_()\s]{1,5})(?<!\.)$/;
                                                        console.log($(this)[0].files[i].name);
                                                        if (english.test($(this)[0].files[i].name)) {
                                                            console.log($(this)[0].files[i].name);
                                                        } else {
                                                            alert('File name error : ' + $(this)[0].files[i].name +
                                                                ' ชื่อไฟล์ต้องเป็นภาษาอังกฤษและตัวเลข 0-9 เท่านั้น');
                                                            files = [];
                                                            $(this).next('.custom-file-label').html('Choose file');
                                                            document.getElementById("tableFile").style.marginTop =
                                                                "0px";
                                                            return false;
                                                        }
                                                    }
                                                    $(this).next('.custom-file-label').html(files.join('<br> '));
                                                    var clientHeightF = document.getElementById("custom-file-label")
                                                        .clientHeight;
                                                    var clientHeight = clientHeightF - 10;
                                                    var h = clientHeight + 'px';
                                                    document.getElementById("tableFile").style.marginTop = h;
                                                });

                                                $('select').on('change', function() {
                                                    document.getElementById("rfq_type_edit").value = this.value;
                                                });
                                            </script>
                                            <!-- //* javascript ไว้สำหรับเด้งแจ้งเตือนให้อัพโหลดไฟล์เฉพาะ png jpg pdf เท่านั้น -->
                                            <div class="row" id="tableFile">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead class="thead-dark" style="font-size: 0.9em;">
                                                                <tr>
                                                                    <th>File name</th>
                                                                    <th>UpdateBy</th>
                                                                    <th>DateCreate</th>
                                                                    <th>DateModified</th>
                                                                    <th>Download / Delete File</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size: small;">
                                                                <?php
                                                                $query1 = "SELECT * FROM quatation_file WHERE Num_req = '$num_req'";
                                                                $returnedValue1 = sqlsrv_query($conn, $query1);
                                                                $row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC);

                                                                if ($row1 === false) {
                                                                    // echo "Error while fetching array.\n";
                                                                    die(print_r(sqlsrv_errors(), true));
                                                                } else if ($row1 === null) {
                                                                    echo "No results were found.\n";
                                                                } else {
                                                                    do {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $row1["File_name"]; ?></td>
                                                                    <td><?php echo $row1["Update_by"]; ?></td>
                                                                    <td><?php echo date_format($row1["Date_create"], "d/m/Y H:i:s"); ?>
                                                                    </td>
                                                                    <td><?php echo date_format($row1["Date_modified"], "d/m/Y H:i:s"); ?>
                                                                    </td>                         
                                                                    <td>
                                                                        <div class="d-flex justify-content-sm-between">
                                                                            <div class="p-1"><a
                                                                                    href="upload/<?php echo $row1["File_name"]; ?>"
                                                                                    class="btn btn-primary" download><i
                                                                                        class="fa fa-download"></i></a>
                                                                            </div>
                                                                            <?php
                                                                            $exts = array('gif', 'png', 'jpg', 'pdf', 'jpeg', 'jfif');

                                                                            if (in_array(strtolower(end(explode('.', $row1["File_name"]))), $exts)) {
                                                                            ?>
                                                                            <div class="p-1"><a
                                                                                    href="readFile.php?file=upload/<?php echo $row1["File_name"] ?>"
                                                                                    class="btn btn-success"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            </div>
                                                                            <div class="p-1"><a
                                                                                    href="db_delete_quatation_file.php?quatation_file_id=<?php echo $row1["quatation_file_id"]; ?>&File_name=<?php echo $row1["File_name"]; ?>&quatation_ID=<?php echo $quatation_ID ?>"
                                                                                    onclick="return confirm('Are you sure to delete file >> <?php echo $row1['File_name']; ?> << ')"
                                                                                    class="btn btn-danger"><i
                                                                                        class="fa fa-trash"
                                                                                        aria-hidden="true"></i>
                                                                                </a>
                                                                            </div>
                                                                            <?php } else {  ?>
                                                                            <div class="p-1"><a
                                                                                    href="db_delete_quatation_file.php?quatation_file_id=<?php echo $row1["quatation_file_id"]; ?>&File_name=<?php echo $row1["File_name"]; ?>&quatation_ID=<?php echo $quatation_ID ?>"
                                                                                    onclick="return confirm('Are you sure to delete file >> <?php echo $row1['File_name']; ?> << ')"
                                                                                    class="btn btn-danger"><i
                                                                                        class="fa fa-trash"
                                                                                        aria-hidden="true"></i>
                                                                                </a>
                                                                            </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                    } while ($row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC));
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12 col-sm-12">
                                                    <label for="comment_user"><i class="fa fa-commenting-o"
                                                            aria-hidden="true"></i>Comment from user</label>
                                                    <textarea class="form-control"
                                                        placeholder="Please , Enter a comment . . ." rows="3"
                                                        name="comment_user"></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 col-sm-12"> <a href="Dashboard.php"
                                                        class="btn btn-block btn-secondary"><i class="fa fa-arrow-left"
                                                            aria-hidden="true"></i>
                                                        Back</a></div>
                                                <div class="col-md-7 col-sm-12"> </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit" onclick="return confirm('ยืนยันการบันทึก');"
                                                        value="submit" name="submit" class="btn btn-block btn-success"><i
                                                            class="fa fa-check" aria-hidden="true"></i>
                                                        Submit44444</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- หลังจาก Purchase Staff หาราคาได้แล้ว Purchase Manager ไม่อนุมัติ, ส่งเมล์กลับให้ Purchase Staff Revise (หาราคาใหม่) -->
                                    <?php
                                    } elseif ($status == 7 && $EmployeeCode == $pu_code) {
                                        // } elseif ($status == 7 && $EmployeeCode == '1100528') {
                                        $output = 'status == 7 user คือ PU หลังจากที่ manager purchase ไม่ approve ให้ ';
                                        // echo "<script>console.log('User : " . $output . "' );</script>";
                                        // echo "<script>console.log('Status now : " . $status . "' );</script>";
                                    ?>
                                    <div class="container-fluid" style="padding: 0px;">
                                        <div class="card">
                                            <div class="card-header"><strong class="card-title"><i class="fa fa-file-text"
                                                        aria-hidden="true"
                                                        style="margin-right: 10px;"></i>แก้ไขข้อมูลสินค้า</i></div>
                                            <div class="card-body">
                                                <!-- <table class="table"> -->
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:60px">Product name</th>
                                                                <th style="min-width:50px">Quantity</th>
                                                                <th style="min-width:50px">Unit</th>
                                                                <th style="background-color: #28a745;color:white">Price</th>
                                                                <th style="min-width:50px">Save</th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        $summary = 0;
                                                        $queryB = "SELECT quatation.*,request_product.* FROM quatation LEFT JOIN request_product ON quatation.num_req = request_product.num_req WHERE quatation_ID = '$quatation_ID' ";
                                                        $returnedB = sqlsrv_query($conn, $queryB);
                                                        $rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC);

                                                        if ($rowB === false) {
                                                            die(print_r(sqlsrv_errors(), true));
                                                        } elseif ($rowB === null) {
                                                            echo "No results were found.\n";
                                                        } else {
                                                            do {
                                                                $summary = $summary + ($rowB['price'] * $rowB['amount']);
                                                                $sum = ($rowB['price'] * $rowB['amount']);
                                                                // echo "<script>console.log('summary :  (" . $rowB['price'] . " * " . $rowB['amount'] . ") = " . $sum  . "' );</script>";
                                                        ?>

                                                        <form action="db_quatation_edit_request_product.php" name="save"
                                                            id="save" method="post" ENCTYPE="multipart/form-data">
                                                        <tbody style="font-size: 0.8em;">
                                                            <td><input style="width:300px" type="text" id="product"
                                                                    name="product"
                                                                    value="<?php echo $rowB['product']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                            <td><input style="width:50px" type="text" id="amount"
                                                                    name="amount" value="<?php echo $rowB['amount']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                            <td><input style="width:50px" type="text" id="unit"
                                                                    name="unit" value="<?php echo $rowB['unit']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                            <td style="background-color: #28a745;"><input
                                                                    style="width:50px" type="text" id="price"
                                                                    name="price" value="<?php echo $rowB['price']; ?>"
                                                                    autocomplete="off" required></td>
                                                            <td hidden="on"><input type="text" id="request_ID"
                                                                    name="request_ID"
                                                                    value="<?php echo $rowB['request_ID']; ?>"
                                                                    autocomplete="off"></td>
                                                            <td hidden="on"><input type="text" id="quatation_ID"
                                                                    name="quatation_ID"
                                                                    value="<?php echo $quatation_ID ?>"
                                                                    autocomplete="off"></td>
                                                            <td><button style="width:50px" type="submit"
                                                                    onclick="return confirm('ยืนยันการบันทึก');"
                                                                    class="btn btn-success btn-xs"><i
                                                                        class="fa fa-floppy-o" aria-hidden="true"></i>
                                                                </button>
                                                            </td>
                                                        </tbody>
                                                        </form>
                                                        <?php
                                                            } while ($rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC));
                                                        }
                                                        // echo "<script>console.log('summary all :  " . $summary . "' );</script>";
                                                        ?>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="4" style="text-align: right;"><span
                                                                        class="btn btn-outline-info btn-block"> Total (บาท)
                                                                        :
                                                                        <?php echo number_format($summary, 0, '', ','); ?></span>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="db_quatation_edit_pu.php" name="save_pu" id="save_pu" method="post"
                                            ENCTYPE="multipart/form-data">
                                            <input id="quatation_ID" name="quatation_ID" type="text"
                                                value="<?php echo  $quatation_ID ?>" hidden>
                                            <input id="num_req" name="num_req" type="text" value="<?php echo  $num_req ?>"
                                                hidden>
                                            <input id="name_request" name="name_request" type="text"
                                                value="<?php echo  $name_request ?>" hidden>
                                            <input id="email" name="email" type="text" value="<?php echo  $email ?>" hidden>
                                            <input id="department" name="department" type="text"
                                                value="<?php echo  $department ?>" hidden>
                                            <input id="tel" name="tel" type="text" value="<?php echo  $tel ?>" hidden>
                                            <input id="date_picker" name="date_picker" type="text"
                                                value="<?php echo $date_picker ?>" hidden>
                                            <input id="status_name" name="status_name" type="text"
                                                value="<?php echo $status_name ?>" hidden>
                                            <input id="comment_pu_last" name="comment_pu_last" type="text"
                                                value="<?php echo  $comment_pu ?>" hidden>
                                            <input id="status" name="status" type="text" value="<?php echo $status ?>"
                                                hidden>              

                                            <div class="row">
                                                <div class="col-12" style=" margin-bottom: 10px;">
                                                    <!-- <label for="input-id"><i class="fa fa-plus" aria-hidden="true"></i> Upload files (Multiple) <label style="color: red;">* .png .jpg .jpeg .pdf .zip .rar .7z (<= 30MB)</label></label> -->
                                                    <label for="input-id"><i class="fa fa-plus" aria-hidden="true"></i>
                                                        Upload files (Multiple) <label style="color: red;">(<= 30MB per file
                                                                ชื่อไฟล์ต้องเป็นภาษาอังกฤษและตัวเลข 0-9 เท่านั้น)</label>
                                                    </label>
                                                    <div class="custom-file" id="custom-file">
                                                        <input type="file" style="height: auto;"
                                                            class="custom-file-input" name="files[]" id="files"
                                                            multiple>
                                                        <label class="custom-file-label" id="custom-file-label"
                                                            style="height: auto;" for="inputGroupFile02">Choose
                                                            file</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- //* javascript ไว้สำหรับเด้งแจ้งเตือนให้อัพโหลดไฟล์เฉพาะ png jpg pdf เท่านั้น -->
                                            <script type="text/javascript">
                                                $('.custom-file input').change(function(e) {
                                                    var files = [];
                                                    // var allowedExtensions =
                                                    //    /(\.jpg|\.jpeg|\.png|\.pdf|\.zip|\.rar|\.7z)$/i;


                                                    for (var i = 0; i < $(this)[0].files.length; i++) {
                                                        files.push($(this)[0].files[i].name);

                                                        const fileSize = $(this)[0].files[i].size / 1024 /
                                                            1024; // in MiB

                                                        // if (!allowedExtensions.exec($(this)[0].files[i].name)) {
                                                        //    alert('Invalid file type , Please use (.pdf/.jpg/.jpeg/.png) only.');
                                                        //    files = [];
                                                        //    $(this).next('.custom-file-label').html('Choose file');
                                                        //    document.getElementById("commentPu").style.marginTop = "0px";
                                                        //    return false;
                                                        // } else {
                                                        if (fileSize > 30) {
                                                            alert('File size exceeds 30 MiB');
                                                            files = [];
                                                            $(this).next('.custom-file-label').html('Choose file');
                                                            document.getElementById("commentPu").style.marginTop =
                                                                "0px";


                                                            return false;
                                                            // $(file).val(''); //for clearing with Jquery
                                                        }

                                                        var english =
                                                            /^([a-zA-Z0-9-_()\s]+)\.(?!\.)([a-zA-Z0-9-_()\s]{1,5})(?<!\.)$/;

                                                        if (english.test($(this)[0].files[i].name)) {

                                                            console.log($(this)[0].files[i].name);

                                                        } else {
                                                            alert('ชื่อไฟล์ต้องเป็นภาษาอังกฤษและตัวเลข 0-9 เท่านั้น');
                                                            files = [];
                                                            $(this).next('.custom-file-label').html('Choose file');
                                                            document.getElementById("commentPu").style.marginTop =
                                                                "0px";
                                                            return false;
                                                        }
                                                        // } else {}
                                                        // }
                                                    }

                                                    $(this).next('.custom-file-label').html(files.join('<br> '));
                                                    var clientHeightF = document.getElementById("custom-file-label")
                                                        .clientHeight;
                                                    var clientHeight = clientHeightF - 10

                                                    var h = clientHeight + 'px';
                                                    document.getElementById("commentPu").style.marginTop = h;
                                                });
                                            </script>
                                            <!-- //* javascript ไว้สำหรับเด้งแจ้งเตือนให้อัพโหลดไฟล์เฉพาะ png jpg pdf เท่านั้น -->

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead class="thead-dark" style="font-size: 0.9em;">
                                                                <tr>
                                                                    <th>File name</th>
                                                                    <th>UpdateBy</th>
                                                                    <th>DateCreate</th>
                                                                    <th>DateModified</th>
                                                                    <th>Download</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size: small;">
                                                                <?php
                                                                $query1 = "SELECT * FROM quatation_file WHERE Num_req = '$num_req'";
                                                                $returnedValue1 = sqlsrv_query($conn, $query1);
                                                                $row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC);

                                                                if ($row1 === false) {
                                                                    // echo "Error while fetching array.\n";
                                                                    die(print_r(sqlsrv_errors(), true));
                                                                } else if ($row1 === null) {
                                                                    echo "No results were found.\n";
                                                                } else {
                                                                    do {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $row1["File_name"]; ?></td>
                                                                    <td><?php echo $row1["Update_by"]; ?></td>
                                                                    <td><?php echo date_format($row1["Date_create"], "d/m/Y H:i:s"); ?>
                                                                    </td>
                                                                    <td><?php echo date_format($row1["Date_modified"], "d/m/Y H:i:s"); ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex justify-content-sm-between">
                                                                            <div class="p-1"><a
                                                                                    href="upload/<?php echo $row1["File_name"]; ?>"
                                                                                    class="btn btn-primary" download><i
                                                                                        class="fa fa-download"></i></a>
                                                                            </div>
                                                                            <?php
                                                                            $exts = array('gif', 'png', 'jpg', 'pdf', 'jpeg', 'jfif');

                                                                            if (in_array(strtolower(end(explode('.', $row1["File_name"]))), $exts)) {
                                                                            ?>
                                                                            <div class="p-1"><a
                                                                                    href="readFile.php?file=upload/<?php echo $row1["File_name"] ?>"
                                                                                    class="btn btn-success"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            </div>
                                                                            <div class="p-1"><a
                                                                                    href="db_delete_quatation_file.php?quatation_file_id=<?php echo $row1["quatation_file_id"]; ?>&File_name=<?php echo $row1["File_name"]; ?>&quatation_ID=<?php echo $quatation_ID ?>"
                                                                                    onclick="return confirm('Are you sure to delete file >> <?php echo $row1['File_name']; ?> << ')"
                                                                                    class="btn btn-danger"><i
                                                                                        class="fa fa-trash"
                                                                                        aria-hidden="true"></i>
                                                                                </a>
                                                                            </div>
                                                                            <?php } else {  ?>
                                                                            <div class="p-1"><a
                                                                                    href="db_delete_quatation_file.php?quatation_file_id=<?php echo $row1["quatation_file_id"]; ?>&File_name=<?php echo $row1["File_name"]; ?>&quatation_ID=<?php echo $quatation_ID ?>"
                                                                                    onclick="return confirm('Are you sure to delete file >> <?php echo $row1['File_name']; ?> << ')"
                                                                                    class="btn btn-danger"><i
                                                                                        class="fa fa-trash"
                                                                                        aria-hidden="true"></i>
                                                                                </a>
                                                                            </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                    } while ($row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC));
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="commentPu">
                                                <div class="form-group col-md-12 col-sm-12">
                                                    <label for="comment_pu"><i class="fa fa-commenting-o"
                                                            aria-hidden="true"></i>Comment from Purchase</label>
                                                    <textarea class="form-control"
                                                        placeholder="Please , Enter a comment . . ." rows="3"
                                                        name="comment_pu"></textarea>

                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-2 col-sm-12"> <a href="Dashboard.php"
                                                        class="btn btn-block btn-secondary"><i class="fa fa-arrow-left"
                                                            aria-hidden="true"></i>
                                                        Back</a></div>
                                                <div class="col-md-1 col-sm-12"> </div>
                                                <div class="col-md-3 col-sm-12 ">
                                                    <button type="submit" onclick="return confirm('ยืนยันการบันทึก');"
                                                        value="submit" name="submit" class="btn btn-block btn-success"><i
                                                            class="fa fa-check" aria-hidden="true"></i>
                                                        Submit5555</button>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit" onclick="return confirm('ยืนยันการบันทึก');"
                                                        value="cancel" name="submit" class="btn btn-block btn-danger"><i
                                                            class="fa fa-times" aria-hidden="true"></i>
                                                        Cancel5555</button>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <a data-toggle="tooltip"
                                                        title="Back to Approver to change Responsibility."
                                                        class="btn btn-block btn-secondary"
                                                        href="db_change_quatation_edit_pu.php?quatation_ID=<?php echo $quatation_ID; ?>&num_req=<?php echo $num_req; ?>&name_request=<?php echo $name_request; ?>&email=<?php echo $email; ?>&department=<?php echo $department; ?>&tel=<?php echo $tel; ?>"
                                                        onclick="return confirm('ยืนยันการบันทึก');"><i
                                                            class="fa fa-refresh" aria-hidden="true"></i> <i
                                                            class="fa fa-user" aria-hidden="true"></i> Change</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- user คือ ผู้ manager จัดซื้อ pending manager purchase -->
                                    <?php
                                    } elseif ($status == 8 && $EmployeeCode == $approver_pu_code) {

                                        // } elseif ($status == 8  && $EmployeeCode == '1100528') {

                                        $output = 'status == 8 Approver Purchase ';
                                        // echo "<script>console.log('User : " . $output . "' );</script>";
                                        // echo "<script>console.log('Status now : " . $status . "' );</script>";
                                    ?>
                                    <div class="container-fluid" style="padding: 0px;">
                                        <div class="card">
                                            <div class="card-header"><strong class="card-title"><i class="fa fa-file-text"
                                                        aria-hidden="true"
                                                        style="margin-right: 10px;"></i>แก้ไขข้อมูลสินค้า</i></div>
                                            <div class="card-body">
                                                <a href="upload/<?php echo $row1["File_name"] ?>" class="btn btn-info"
                                                    download><i class="fa fa-eye"></i></a>
                                                <!-- <table class="table"> -->
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="min-width:300px">Product name</th>
                                                                <th style="min-width:50px">Quantity</th>
                                                                <th style="min-width:50px">Unit</th>
                                                                <th style="min-width:50px">Price</th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        $summary = 0;
                                                        $queryB = "SELECT quatation.*,request_product.* FROM quatation LEFT JOIN request_product ON quatation.num_req = request_product.num_req WHERE quatation_ID = '$quatation_ID' ";
                                                        $returnedB = sqlsrv_query($conn, $queryB);
                                                        $rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC);

                                                        if ($rowB === false) {
                                                            die(print_r(sqlsrv_errors(), true));
                                                        } elseif ($rowB === null) {
                                                            echo "No results were found.\n";
                                                        } else {
                                                            do {
                                                                $summary = $summary + ($rowB['price'] * $rowB['amount']);
                                                                $sum = ($rowB['price'] * $rowB['amount']);
                                                                // echo "<script>console.log('summary :  (" . $rowB['price'] . " * " . $rowB['amount'] . ") = " . $sum  . "' );</script>";
                                                        ?>
                                                        <form action="db_quatation_edit_request_product.php" name="save"
                                                            id="save" method="post" ENCTYPE="multipart/form-data">
                                                        <tbody style="font-size: 0.8em;">
                                                            <td><input style="width:300px" type="text" id="product"
                                                                    name="product"
                                                                    value="<?php echo $rowB['product']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                            <td><input style="width:50px" type="text" id="amount"
                                                                    name="amount" value="<?php echo $rowB['amount']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                            <td><input style="width:50px" type="text" id="unit"
                                                                    name="unit" value="<?php echo $rowB['unit']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                            <td><input style="width:50px" type="text" id="price"
                                                                    name="price" value="<?php echo $rowB['price']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                            </td>
                                                        </tbody>
                                                        </form>
                                                        <?php
                                                            } while ($rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC));
                                                        }
                                                        // echo "<script>console.log('summary all :  " . $summary . "' );</script>";
                                                        ?>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="4" style="text-align: right;"><span
                                                                        class="btn btn-outline-info btn-block"> Total (บาท)
                                                                        :
                                                                        <?php echo number_format($summary, 0, '', ','); ?></span>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="db_quatation_edit_approver_pu_last.php" name="save_approver_pu_last"
                                            id="save_approver_pu_last" method="post" ENCTYPE="multipart/form-data">
                                            <input id="quatation_ID" name="quatation_ID" type="text"
                                                value="<?php echo  $quatation_ID ?>" hidden>
                                            <input id="num_req" name="num_req" type="text" value="<?php echo  $num_req ?>"
                                                hidden>
                                            <input id="name_request" name="name_request" type="text"
                                                value="<?php echo  $name_request ?>" hidden>
                                            <input id="email" name="email" type="text" value="<?php echo  $email ?>" hidden>
                                            <input id="department" name="department" type="text"
                                                value="<?php echo  $department ?>" hidden>
                                            <input id="tel" name="tel" type="text" value="<?php echo  $tel ?>" hidden>

                                            <input id="status_name" name="status_name" type="text"
                                                value="<?php echo  $status_name ?>" hidden>
                                            <input id="pu_code" name="pu_code" type="text" value="<?php echo  $pu_code ?>"
                                                hidden>
                                            <input id="comment_approver_pu_last" name="comment_approver_pu_last" type="text"
                                                value="<?php echo  $comment_approver_pu ?>" hidden>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead class="thead-dark" style="font-size: 0.9em;">
                                                                <tr>
                                                                    <th>File name</th>
                                                                    <th>UpdateBy</th>
                                                                    <th>DateCreate</th>
                                                                    <th>DateModified</th>
                                                                    <th>Download</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size: small;">
                                                                <?php
                                                                $query1 = "SELECT * FROM quatation_file WHERE Num_req = '$num_req'";
                                                                $returnedValue1 = sqlsrv_query($conn, $query1);
                                                                $row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC);

                                                                if ($row1 === false) {
                                                                    // echo "Error while fetching array.\n";
                                                                    die(print_r(sqlsrv_errors(), true));
                                                                } else if ($row1 === null) {
                                                                    echo "No results were found.\n";
                                                                } else {
                                                                    do {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $row1["File_name"]; ?></td>
                                                                    <td><?php echo $row1["Update_by"]; ?></td>
                                                                    <td><?php echo date_format($row1["Date_create"], "d/m/Y H:i:s"); ?>
                                                                    </td>
                                                                    <td><?php echo date_format($row1["Date_modified"], "d/m/Y H:i:s"); ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex justify-content-sm-between">
                                                                            <div class="p-1"><a
                                                                                    href="upload/<?php echo $row1["File_name"]; ?>"
                                                                                    class="btn btn-primary" download><i
                                                                                        class="fa fa-download"></i></a>
                                                                            </div>
                                                                            <?php
                                                                            $exts = array('gif', 'png', 'jpg', 'pdf', 'jpeg', 'jfif');

                                                                            if (in_array(strtolower(end(explode('.', $row1["File_name"]))), $exts)) {
                                                                            ?>
                                                                            <div class="p-1"><a
                                                                                    href="readFile.php?file=upload/<?php echo $row1["File_name"] ?>"
                                                                                    class="btn btn-success"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            </div>
                                                                            <?php }   ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                    } while ($row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC));
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>      
                                            <div class="row">
                                                <div class="form-group col-md-12 col-sm-12">
                                                    <label for="comment_from_pu"><i class="fa fa-commenting-o"
                                                            aria-hidden="true"></i> Comment from Approver Purchase</label>
                                                    <textarea class="form-control"
                                                        placeholder="Please , Enter a comment . . ." rows="3"
                                                        name="comment_approver_pu"></textarea>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 col-sm-12"> <a href="Dashboard.php"
                                                        class="btn btn-block btn-secondary"><i class="fa fa-arrow-left"
                                                            aria-hidden="true"></i>
                                                        Back</a></div>
                                                <div class="col-md-4 col-sm-12"> </div>

                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit" value="submit" name="submit"
                                                        onclick=" return confirm('ยืนยันการบันทึก')"
                                                        class="btn btn-block btn-success"><i class="fa fa-check"
                                                            aria-hidden="true"></i>
                                                        Submit6666</button>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit" value="cancel" name="submit"
                                                        onclick=" return confirm('ยืนยันการบันทึก')"
                                                        class="btn btn-block btn-danger"><i class="fa fa-times"
                                                            aria-hidden="true"></i>
                                                        Cancel6666</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- user คือ user ทั่วไป -->
                                    <?php } else {
                                        $output = 'case:else  User ทั่วไป';
                                        // echo "<script>console.log('User : " . $output . "' );</script>";
                                        // echo "<script>console.log('Status now : " . $status . "' );</script>";
                                    ?>
                                    <div class="container-fluid" style="padding: 0px;">
                                        <div class="card">
                                            <div class="card-header"><strong class="card-title"><i class="fa fa-file-text"
                                                        aria-hidden="true" style="margin-right: 10px;"></i>ข้อมูลสินค้า</i>
                                            </div>
                                            <div class="card-body">
                                                <!-- <table class="table"> -->
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>

                                                                <th style="min-width:300px">Product name</th>
                                                                <th style="min-width:50px">Quantity</th>
                                                                <th style="min-width:50px">Unit</th>
                                                                <th style="min-width:50px">Price</th>

                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        $summary = 0;
                                                        $queryB = "SELECT quatation.*,request_product.* FROM quatation LEFT JOIN request_product ON quatation.num_req = request_product.num_req WHERE quatation_ID = '$quatation_ID' ";
                                                        $returnedB = sqlsrv_query($conn, $queryB);
                                                        $rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC);
                                                        if ($rowB === false) {
                                                            die(print_r(sqlsrv_errors(), true));
                                                        } elseif ($rowB === null) {
                                                            echo "No results were found.\n";
                                                        } else {
                                                            do {
                                                                $summary = $summary + ($rowB['price'] * $rowB['amount']);
                                                                $sum = ($rowB['price'] * $rowB['amount']);
                                                                // echo "<script>console.log('summary :  (" . $rowB['price'] . " * " . $rowB['amount'] . ") = " . $sum  . "' );</script>";
                                                        ?>
                                                        <tbody style="font-size: 0.8em;">
                                                            <td><input style="width:300px" type="text" id="product"
                                                                    name="product" value="<?php echo $rowB['product']; ?>"
                                                                    autocomplete="off" readonly>
                                                            </td>
                                                            <td><input style="width:50px" type="text" id="amount"
                                                                    name="amount" value="<?php echo $rowB['amount']; ?>"
                                                                    autocomplete="off" readonly></td>
                                                            <td><input style="width:50px" style="width:50px" type="text"
                                                                    id="unit" name="unit"
                                                                    value="<?php echo $rowB['unit']; ?>" autocomplete="off"
                                                                    readonly></td>
                                                            <td><input style="width:50px" style="width:50px" type="text"
                                                                    id="price" name="price"
                                                                    value="<?php echo $rowB['price']; ?>" autocomplete="off"
                                                                    readonly></td>
                                                        </tbody>
                                                        </form>
                                                        <?php
                                                            } while ($rowB = sqlsrv_fetch_array($returnedB, SQLSRV_FETCH_ASSOC));
                                                        }
                                                        // echo "<script>console.log('summary all :  " . $summary . "' );</script>";
                                                        ?>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="4" style="text-align: right;"><span
                                                                        class="btn btn-outline-info btn-block"> Total (บาท)
                                                                        :
                                                                        <?php echo number_format($summary, 0, '', ','); ?></span>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead class="thead-dark" style="font-size: 0.9em;">
                                                            <tr>
                                                                <th>File name</th>
                                                                <th>UpdateBy</th>
                                                                <th>DateCreate</th>
                                                                <th>DateModified</th>
                                                                <th>Download</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody style="font-size: small;">
                                                            <?php
                                                            $query1 = "SELECT * FROM quatation_file WHERE Num_req = '$num_req'";
                                                            $returnedValue1 = sqlsrv_query($conn, $query1);
                                                            $row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC);
                                                            if ($row1 === false) {
                                                                // echo "Error while fetching array.\n";
                                                                die(print_r(sqlsrv_errors(), true));
                                                            } else if ($row1 === null) {
                                                                echo "No results were found.\n";
                                                            } else {
                                                                do {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $row1["File_name"]; ?></td>
                                                                <td><?php echo $row1["Update_by"]; ?></td>
                                                                <td><?php echo date_format($row1["Date_create"], "d/m/Y H:i:s"); ?>
                                                                </td>
                                                                <td><?php echo date_format($row1["Date_modified"], "d/m/Y H:i:s"); ?>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex justify-content-sm-between">
                                                                        <div class="p-1"><a
                                                                                href="upload/<?php echo $row1["File_name"]; ?>"
                                                                                class="btn btn-primary" download><i
                                                                                    class="fa fa-download"></i></a>
                                                                        </div>
                                                                    <?php
                                                                    $exts = array('gif', 'png', 'jpg', 'pdf', 'jpeg', 'jfif');

                                                                    if (in_array(strtolower(end(explode('.', $row1["File_name"]))), $exts)) {
                                                                    ?>
                                                                    <div class="p-1"><a
                                                                            href="readFile.php?file=upload/<?php echo $row1["File_name"] ?>"
                                                                            class="btn btn-success" target="_blank"><i
                                                                                class="fa fa-eye"></i></a>
                                                                    </div>
                                                                    <?php } ?>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                                } while ($row1 = sqlsrv_fetch_array($returnedValue1, SQLSRV_FETCH_ASSOC));
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
        <?php
        echo "<script>console.log('REQ : ' + '" . $num_req . "');</script>";
        echo "<script>console.log('status = ' + '" . $status . "' + ' | ' +  '" .  $status_name . "');</script>";
        echo "<script>console.log('plant = ' + '" . $EmployeePlantCode . "' + ' | dept =  ' +  '" .  $DepartmentCode . "');</script>";
        echo "<script>console.log('User : ' + '" . $name_request  . "' + ' | ' + '" . $employee_code_request . "');</script>";
        echo "<script>console.log('Approver user_ : ' + '" . $approver_user_nameTH . "' + ' | ' + '" .  $approver_user_code . "' + ' | ' + '" .  $CurrentDMGMMDApproverCode . "');</script>";
        echo "<script>console.log('Approver pu : ' + '" . $approver_pu_nameTH . "' + ' | '  + '" .  $approver_pu_code . "');</script>";
        echo "<script>console.log('pu : ' + '" . $pu_nameTH . "' + ' | ' + '" . $pu_code . "');</script>";
        ?>
    </div>
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
    <div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Confirm Submit
                </div>
                <div class="modal-body">
                    Are you sure you want to submit ?
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="return confirm('ยืนยันการบันทึก');" class="btn btn-default"
                        data-dismiss="modal">Cancel</button>
                    <a type="submit" onclick="return confirm('ยืนยันการบันทึก');" id="submit"
                        class="btn btn-success success">Submit</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Jquery JS-->
    <!-- <script src="vendor/jquery-3.2.1.min.js"></script> -->
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js">
    </script>
    <!-- Main JS-->
    <script src="js/main.js"></script>
    <script src="js/swallow.js"></script>
    <script>
    var myVar;

    function myFunction() {
        myVar = setTimeout(showPage, 500);
    }

    function showPage() {
        document.getElementById("loader").style.display = "none";
        document.getElementById("myDiv").style.display = "block";
    }
    </script>

    <script>
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',

        autoclose: true

    });
    </script>
</body>
</html>

<!-- end document-->
<?php sqlsrv_close($conn); ?>