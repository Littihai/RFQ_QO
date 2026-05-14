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


$sqlT = "SELECT * FROM vw_Employee where EmployeeUsername = '$Username' ";
$queryT = sqlsrv_query($conn, $sqlT);
$resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC);
if (!$resultT) {

    die(print_r(sqlsrv_errors(), true));
} else if ($resultT === null) {
    echo "No results were found.\n";
} else {
    do {
        $EmployeeCode = $resultT["EmployeeCode"];
        $EmployeeThFirstName = $resultT["ThFirstName"];
        $EmployeeThLastName = $resultT["ThLastName"];
    } while ($resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC));
}

// วรชัย
// $EmployeeCode = '1600573';
// กฤษขจร
// $EmployeeCode = '1600105';
// เกรียงไกร
// $EmployeeCode = '1600644';
// พิทยา
// $EmployeeCode = '1600155';

// $EmployeeCode = '1600318';

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
<?php
// ============================ Add Start Date When Click Edit 04726 20260401 ===========================
// SQL ดึง StartDate_LT ของ Admin ปัจจุบัน
    $sql = "
    SELECT A.RFQNum, A.StartDate_LT, A.EndDate_LT
    FROM admin_purchase B
    LEFT JOIN TSE_CateLeadTime A 
        ON A.BuyerNum = B.EmployeeCode
    WHERE B.EmployeeCode = '$EmployeeCode'
    AND A.StartDate_LT IS NOT NULL
    ";

    $query = sqlsrv_query($conn, $sql);

    // สร้าง array เก็บ RFQNum ที่มี StartDate_LT
    $rfqStarted = [];
    while ($rowStart = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $rfqStarted[$rowStart['RFQNum']] = [
            'StartDate_LT' => $rowStart['StartDate_LT'],
            'EndDate_LT'   => $rowStart['EndDate_LT']
        ];
    }


    $sqlRFQ = "SELECT * FROM quatation";
    $queryRFQ = sqlsrv_query($conn, $sqlRFQ);
    while ($row = sqlsrv_fetch_array($queryRFQ, SQLSRV_FETCH_ASSOC)) {

        if (isset($rfqStarted[$row['num_req']])) {

            $endDate = $rfqStarted[$row['num_req']]['EndDate_LT'];

            if (empty($endDate)) {
                // ยังไม่จบ → สีเหลือง
                echo '<a class="btn btn-warning btn-xs">
                        <i class="fa fa-clock text-white"></i>
                    </a>';
            } else {
                // จบแล้ว → สีเขียว
                echo '<a class="btn btn-success btn-xs">
                        <i class="fa fa-clock text-white"></i>
                    </a>';
            }

        }
    }
// ================================================================================
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- <title>Purchase</title> -->

    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="css/buttons.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="css/fixedHeader.dataTables.min.css">
    <style>
        form {
            margin: 20px 0;
        }

        form input,
        button {
            padding: 5px;
        }


        table.dataTable td {
            font-size: 0.9em;
        }

        table.dataTable th {
            font-size: 0.9em;
        }


        div.dt-button-collection {
            width: 20vh !important;
            height: auto !important;
            margin-top: 20px;


        }

        div.dt-button-collection button.dt-button {
            display: inline-block;
            /* width: 32%; */
            margin-top: 0px;
            margin-left: 0px;
            z-index: 999;


        }

        div.dt-button-collection button.buttons-colvis {
            display: inline-block;
            width: 49%;


        }

        div.dt-button-collection h3 {


            margin-top: 5px;
            margin-bottom: 5px;

            border-bottom: 1px solid black;
            font-size: 1em;
            color: black !important;
        }

        div.dt-button-collection h3.not-top-heading {
            margin-top: 10px;
        }

        .dt-button {
            z-index: 999;
            margin-top: 20px;
            margin-left: 20px;
            border: none !important;
            outline: none !important;
            padding-left: 18px !important;
            -webkit-border-radius: 3px !important;
            -moz-border-radius: 3px !important;
            border-radius: 3px !important;
            height: 40px !important;
            background: #444 !important;
            display: -webkit-box !important;
            display: -webkit-flex !important;
            display: -moz-box !important;
            display: -ms-flexbox !important;
            display: flex !important;
            -webkit-box-align: center !important;
            -webkit-align-items: center !important;
            -moz-box-align: center !important;
            -ms-flex-align: center !important;
            align-items: center !important;
            color: #fff !important;
            font-size: 14px !important;

            border-color: #fff transparent transparent transparent;
        }

        .dt-button-collection {


            display: block;
            height: 450px !important;


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

        #btnControlFont {

            font-size: small;
            width: 250px !important;
            word-wrap: break-word !important;
            white-space: normal !important;

        }


        .stepper-wrapper {
            font-family: Arial;
            /* margin-top: 10px; */
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            padding-top: 10px;

            @media (max-width: 768px) {
                font-size: 12px;
            }
        }

        .stepper-item::before {
            position: absolute;
            content: "";
            border-bottom: 2px solid #ccc;
            width: 100%;
            top: 16px;
            left: -50%;
            z-index: 2;
        }

        .stepper-item::after {
            position: absolute;
            content: "";
            border-bottom: 2px solid #ccc;
            width: 100%;
            top: 16px;
            left: 50%;
            z-index: 2;
        }

        .stepper-item .step-counter {
            position: relative;
            z-index: 5;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 15px;
            height: 15px;
            border-radius: 100%;
            background: #ccc;
            margin-bottom: 0px;
            border-color: white;
            border-style: solid;
            border-width: thin;
        }

        .stepper-item.active {
            font-weight: bold;
        }

        .stepper-item.completed .step-counter {
            background-color: #4bb543;
        }

        .stepper-item.completed::after {
            position: absolute;
            content: "";
            border-bottom: 2px solid #4bb543;
            width: 100%;
            top: 16px;
            left: 50%;
            z-index: 3;
        }

        .stepper-item:first-child::before {
            content: none;
        }

        .stepper-item:last-child::after {
            content: none;
        }

        .progress {
            margin-bottom: 5px !important;
        }
    </style>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
    <script src="js/jquery-3.5.1.min.js"></script>




</head>

<body>

</body>

</html>


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


</head>

<!-- <body class="animsition"> -->



<body onload="myFunction()" style="margin:0;background-image: url('images/imageedit_1_5977293344.jpg');
                    background-repeat: no-repeat;
                    background-attachment: fixed;
                    background-size: 100% 110%;
                    ">

    <div id="loader">
        <img id="loader-image" src="DoubleRing-1s-200px.gif" alt="Loading..." /><br />
    </div>

    <div id="myDiv" class="animate-bottom">
        <!-- <body> -->
        <div class="page-wrapper" style="background-color: #ffffff00; overflow-y: hidden;">
            <!-- HEADER MOBILE-->
            <header class="header-mobile d-block d-lg-none">
                <div class="header-mobile__bar">
                    <div class="container-fluid">
                        <div class="header-mobile-inner">
                            <a class="logo" href="index.html">

                                <h1>Request for quotation</h1> <!-- <img src="images/icon/logo.png" alt="CoolAdmin" /> -->
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
                            <li class="active has-sub">
                                <!-- <a class="js-arrow" href="Home.php"> -->
                                <a href="#">
                                    <i class="fas fa-home"></i>Dashboard</a>
                            </li>
                            <li>

                                <a href="Quatation.php">
                                    <i class="fas fa-group"></i>Quotation</a>
                            </li>


                            <li>
                                <!-- <a class="js-arrow" href="logout.php"> -->
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
                        <h3>Request for Quotation</h3> <!-- <img src="images/icon/logo.png" alt="Cool Admin" /> -->
                    </a>
                </div>
                <div class="menu-sidebar__content js-scrollbar1">
                    <nav class="navbar-sidebar">
                        <ul class="list-unstyled navbar__list">
                            <li class="active has-sub">

                                <a href="#"><i class="fas fa-home"></i>Dashboard</a>

                            </li>
                            <li>
                                <a href="Quatation.php">
                                    <i class="fas fa-align-justify"></i>Quotation</a>
                            </li>
                            <?php if ($statusAdmin == 'yes') { ?>
                                <li>
                                    <a href="Admin_edit.php">
                                        <i class="fa fa-lock"></i>Edit Admin</a>
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

                                            <a class="js-arrow" href="#">


                                                <i class="fas fa-user"></i><?php echo $EmployeeThFirstName; ?> <?php echo $EmployeeThLastName; ?>
                                                <?php echo  $EmployeeCode; ?>

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
            <div class="page-container" style="background-color:transparent; ">
                <!-- MAIN CONTENT-->
                <div class="main-content">
                    <div class="section__content section__content--p30">
                        <div class="container-fluid">


                            <!-- โชว์ alert -->
                            <?php
                            // session_start();
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





                            <h2 class="m-b-20" style="color: white;">Dashboard</h2>
                            <div class="top-campaign">




                                <?php

                                //* ------------------------- All -----------------------
                                $rowcountAll = 0;

                                $sqlAll = "SELECT * FROM quatation where (employee_code_request = '$EmployeeCode' or approver_user_code = '$EmployeeCode' or approver_pu_code = '$EmployeeCode' or pu_code = '$EmployeeCode' or approver_gm_code = '$EmployeeCode' or approver_md_code = '$EmployeeCode') ";

                                $stmtAll = sqlsrv_query($conn, $sqlAll);
                                if ($stmtAll === false) {
                                    die(print_r(sqlsrv_errors(), true));
                                }

                                while ($row = sqlsrv_fetch_array($stmtAll, SQLSRV_FETCH_ASSOC)) {
                                    $rowcountAll++;
                                }


                                sqlsrv_free_stmt($stmtAll);
                                //* ------------------------- All -----------------------

                                //* ------------------------- Inprocess -----------------------
                                $rowcountInprocess = 0;
                                $sqlInprocess = "SELECT * FROM quatation WHERE ((status < '4' OR status > '5') and (employee_code_request = '$EmployeeCode' or approver_user_code = '$EmployeeCode' or approver_pu_code = '$EmployeeCode' or pu_code = '$EmployeeCode' or approver_gm_code = '$EmployeeCode' or approver_md_code = '$EmployeeCode' )) ";
                                $stmtInprocess = sqlsrv_query($conn, $sqlInprocess);
                                if ($stmtInprocess === false) {
                                    die(print_r(sqlsrv_errors(), true));
                                }
                                while ($row = sqlsrv_fetch_array($stmtInprocess, SQLSRV_FETCH_ASSOC)) {
                                    $rowcountInprocess++;
                                }
                                sqlsrv_free_stmt($stmtInprocess);
                                //* ------------------------- Inprocess -----------------------

                                //* ------------------------- Success -----------------------
                                $rowcountSuccess = 0;
                                $sql = "SELECT * FROM quatation WHERE ((status = '4') and (employee_code_request = '$EmployeeCode' or approver_user_code = '$EmployeeCode' or approver_pu_code = '$EmployeeCode' or pu_code = '$EmployeeCode' or approver_gm_code = '$EmployeeCode' or approver_md_code = '$EmployeeCode' )) ";
                                $stmt = sqlsrv_query($conn, $sql);
                                if ($stmt === false) {
                                    die(print_r(sqlsrv_errors(), true));
                                }
                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    $rowcountSuccess++;
                                }
                                sqlsrv_free_stmt($stmt);
                                //* ------------------------- Success -----------------------

                                //* ------------------------- unSuccess -----------------------
                                $rowcountUnsuccess = 0;
                                $sqlUnsuccess = "SELECT * FROM quatation WHERE ((status = '5') and (employee_code_request = '$EmployeeCode' or approver_user_code = '$EmployeeCode' or approver_pu_code = '$EmployeeCode' or pu_code = '$EmployeeCode' or approver_gm_code = '$EmployeeCode' or approver_md_code = '$EmployeeCode' )) ";
                                $stmtUnsuccess = sqlsrv_query($conn, $sqlUnsuccess);
                                if ($stmtUnsuccess === false) {
                                    die(print_r(sqlsrv_errors(), true));
                                }
                                while ($row = sqlsrv_fetch_array($stmtUnsuccess, SQLSRV_FETCH_ASSOC)) {
                                    $rowcountUnsuccess++;
                                }
                                sqlsrv_free_stmt($stmtUnsuccess);
                                //* ------------------------- unSuccess -----------------------
                                ?>

                                <!-- โชว์ข้อมูลบน console log -->
                                <span id="storage" data-variable-1="<?php echo $rowcountAll; ?>" data-variable-2="<?php echo $rowcountInprocess; ?>" data-variable-3="<?php echo $rowcountSuccess; ?>" data-variable-4="<?php echo $rowcountUnsuccess; ?>"></span>
                                <script>
                                    count_job_all = document.getElementById("storage").getAttribute("data-variable-1");
                                    count_job_inprocess = document.getElementById("storage").getAttribute("data-variable-2");
                                    count_job_success = document.getElementById("storage").getAttribute("data-variable-3");
                                    count_job_unsuccess = document.getElementById("storage").getAttribute("data-variable-4");
                                    console.log("count_job_all -> " + count_job_all);
                                    console.log("count_job_inprocess -> " + count_job_inprocess);
                                    console.log("count_job_success -> " + count_job_success);
                                    console.log("count_job_unsuccess -> " + count_job_unsuccess);
                                </script>

                                <?php
                                $count_job_unsuccess_month = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                                $year = date('Y');
                                $month = date('m');
                                $sqlP1 = "SELECT *  FROM quatation where ((status = '5') and (employee_code_request = '$EmployeeCode' or approver_user_code = '$EmployeeCode' or approver_pu_code = '$EmployeeCode' or pu_code = '$EmployeeCode' or approver_gm_code = '$EmployeeCode' or approver_md_code = '$EmployeeCode' )) ";
                                $queryP1 = sqlsrv_query($conn, $sqlP1);
                                while ($resultP1 = sqlsrv_fetch_array($queryP1, SQLSRV_FETCH_ASSOC)) {
                                    // echo "<h2>" . $resultP1["status"] . "</h2>";
                                    $mymonth = date_format($resultP1["date_time_stamp"], "m");
                                    $myyear = date_format($resultP1["date_time_stamp"], "Y");

                                    if ($myyear == $year) {
                                        if ($mymonth == '1') {
                                            $count_job_unsuccess_month[0]++;
                                        } elseif ($mymonth == '2') {
                                            $count_job_unsuccess_month[1]++;
                                        } elseif ($mymonth == '3') {
                                            $count_job_unsuccess_month[2]++;
                                        } elseif ($mymonth == '4') {
                                            $count_job_unsuccess_month[3]++;
                                        } elseif ($mymonth == '5') {
                                            $count_job_unsuccess_month[4]++;
                                        } elseif ($mymonth == '6') {
                                            $count_job_unsuccess_month[5]++;
                                        } elseif ($mymonth == '7') {
                                            $count_job_unsuccess_month[6]++;
                                        } elseif ($mymonth == '8') {
                                            $count_job_unsuccess_month[7]++;
                                        } elseif ($mymonth == '9') {
                                            $count_job_unsuccess_month[8]++;
                                        } elseif ($mymonth == '10') {
                                            $count_job_unsuccess_month[9]++;
                                        } elseif ($mymonth == '11') {
                                            $count_job_unsuccess_month[10]++;
                                        } else {
                                            $count_job_unsuccess_month[11]++;
                                        }
                                    }
                                }
                                // ใช้ echo แสดงข้อมูลบนหน้าจอ
                                // echo "<h2>OUT " . $count_job_unsuccess_month[10] . "</h2>";

                                $count_job_success_month = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                                $sqlP2 = "SELECT * FROM quatation WHERE ((status = '4')and (employee_code_request = '$EmployeeCode' or approver_user_code = '$EmployeeCode' or approver_pu_code = '$EmployeeCode' or pu_code = '$EmployeeCode' or approver_gm_code = '$EmployeeCode' or approver_md_code = '$EmployeeCode' )) ";
                                $queryP2 = sqlsrv_query($conn, $sqlP2);
                                while ($resultP2 = sqlsrv_fetch_array($queryP2, SQLSRV_FETCH_ASSOC)) {
                                    $mymonth = date_format($resultP2["date_time_stamp"], "m");
                                    $myyear = date_format($resultP2["date_time_stamp"], "Y");
                                    if ($myyear == $year) {
                                        if ($mymonth == '1') {
                                            $count_job_success_month[0]++;
                                        } elseif ($mymonth == '2') {
                                            $count_job_success_month[1]++;
                                        } elseif ($mymonth == '3') {
                                            $count_job_success_month[2]++;
                                        } elseif ($mymonth == '4') {
                                            $count_job_success_month[3]++;
                                        } elseif ($mymonth == '5') {
                                            $count_job_success_month[4]++;
                                        } elseif ($mymonth == '6') {
                                            $count_job_success_month[5]++;
                                        } elseif ($mymonth == '7') {
                                            $count_job_success_month[6]++;
                                        } elseif ($mymonth == '8') {
                                            $count_job_success_month[7]++;
                                        } elseif ($mymonth == '9') {
                                            $count_job_success_month[8]++;
                                        } elseif ($mymonth == '10') {
                                            $count_job_success_month[9]++;
                                        } elseif ($mymonth == '11') {
                                            $count_job_success_month[10]++;
                                        } else {
                                            $count_job_success_month[11]++;
                                        }
                                    }
                                }

                                $count_job_inprocess_month = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                                $sqlP3 = "SELECT * FROM quatation WHERE ((status < '4' OR status > '5')and (employee_code_request = '$EmployeeCode' or approver_user_code = '$EmployeeCode' or approver_pu_code = '$EmployeeCode' or pu_code = '$EmployeeCode' or approver_gm_code = '$EmployeeCode' or approver_md_code = '$EmployeeCode' )) ";
                                $queryP3 = sqlsrv_query($conn, $sqlP3);
                                while ($resultP3 = sqlsrv_fetch_array($queryP3, SQLSRV_FETCH_ASSOC)) {
                                    $mymonth = date_format($resultP3["date_time_stamp"], "m");
                                    $myyear = date_format($resultP3["date_time_stamp"], "Y");
                                    if ($myyear == $year) {
                                        if ($mymonth == '1') {
                                            $count_job_inprocess_month[0]++;
                                        } elseif ($mymonth == '2') {
                                            $count_job_inprocess_month[1]++;
                                        } elseif ($mymonth == '3') {
                                            $count_job_inprocess_month[2]++;
                                        } elseif ($mymonth == '4') {
                                            $count_job_inprocess_month[3]++;
                                        } elseif ($mymonth == '5') {
                                            $count_job_inprocess_month[4]++;
                                        } elseif ($mymonth == '6') {
                                            $count_job_inprocess_month[5]++;
                                        } elseif ($mymonth == '7') {
                                            $count_job_inprocess_month[6]++;
                                        } elseif ($mymonth == '8') {
                                            $count_job_inprocess_month[7]++;
                                        } elseif ($mymonth == '9') {
                                            $count_job_inprocess_month[8]++;
                                        } elseif ($mymonth == '10') {
                                            $count_job_inprocess_month[9]++;
                                        } elseif ($mymonth == '11') {
                                            $count_job_inprocess_month[10]++;
                                        } else {
                                            $count_job_inprocess_month[11]++;
                                        }
                                    }
                                }
                                ?>
                                <div class="row ">
                                    <div class="col-lg-12">
                                        <section class="statistic statistic2" style="padding-top: 0px;">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="statistic__item statistic__item--blue">
                                                            <h2 class="number"><?php echo $rowcountAll ?></h2>
                                                            <span class="desc"><b>REQ Quotation All</b></span>
                                                            <div class="icon">
                                                                <i class="zmdi zmdi-calendar-note"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="statistic__item statistic__item--orange">
                                                            <h2 class="number"><?php echo $rowcountInprocess ?></h2>
                                                            <span class="desc"><b>REQ Quotation Inprocess</b></span>
                                                            <div class="icon">
                                                                <i class="zmdi zmdi-code-setting"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="statistic__item statistic__item--green">
                                                            <h2 class="number"><?php echo $rowcountSuccess ?></h2>
                                                            <span class="desc"><b>REQ Quotation Success</b></span>
                                                            <div class="icon">
                                                                <i class="zmdi zmdi-check-all"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="statistic__item statistic__item--red">
                                                            <h2 class="number"><?php echo $rowcountUnsuccess ?></h2>
                                                            <span class="desc"><b>REQ Quotation Unsuccess</b></span>
                                                            <div class="icon">
                                                                <i class="zmdi zmdi-close-circle-o"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>



                                    </div>

                                </div>





                                <div class="row" style=" margin:0 0 auto auto;">
                                    <div class="col-md-7 col-lg-7">
                                        <!-- CHART-->
                                        <div class="container-fluid" style="background-color: white; padding: 40px 40px; height: 100%; ">
                                            <h3 class="title-3 m-b-30">Chart Job All In Year <span class="badge badge-danger"><?php echo $year ?></span></h3>


                                            <canvas id="myChart3"></canvas>

                                            <!-- <div>
                                    <span class="big"><?php echo $rowcountAll ?></span>
                                    <span>/ 16220 items sold</span>
                                 </div> -->
                                        </div>
                                        <!-- END CHART-->
                                    </div>

                                    <div class="col-md-5 col-lg-5">
                                        <!-- CHART PERCENT-->
                                        <div class="container-fluid" style="background-color: white; padding: 40px 40px;  height: 100%; ">
                                            <h3 class=" title-3 m-b-10">chart by All Job</h3>
                                            <canvas id="myChart2"></canvas>

                                            <!-- CHART PERCENT-->
                                        </div>

                                    </div>

                                </div>



 
                                <div class="row">
                                    <div class="col-lg-12">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h2>Data of Request for Quotation</h2>
                                                <h3 class="title-5 m-b-10">All Data table</h3>


                                                <table id="example1" class="table table-hover " style="width:100% ">
                                                    <thead class="thead-dark">
                                                        <tr style="font-size: 0.9em; ">

                                                            <th style="min-width:250px">Status</th>
                                                            <!-- <th style="min-width:140px">(View/Edit)/Delete</th> -->

                                                            <!-- <th style="min-width:150px">(View/Edit)</th>  --> <!-- Comment for add clock 04726 20260402 -->
                                                            <th style="min-width:150px">(View/Edit)</th> 
                                                            <th>No.</th>
                                                            <th style="min-width:100px">Req</th>
                                                            <th style="min-width:150px">Product Name</th>
                                                            <th style="min-width:100px">NameRequest</th>
                                                            <th>EmployeeCode</th>
                                                            <th style="min-width:150">Department</th>
                                                            <th style="min-width:100px">Tel</th>
                                                            <th style="min-width:150px">Email</th>

                                                            <th>Desired date</th>
                                                            <th>Created Date</th>
                                                            <th>Update Date</th>
                                                            <!-- <th>Pdf download</th> -->


                                                        </tr>
                                                    </thead>
                                                    <tbody style="font-size: 0.8em; ">
                                                        <style>
                                                            th {
                                                                background-color: LightSlateGrey;
                                                                color: white;
                                                            }

                                                            td {
                                                                height: 50px;
                                                                vertical-align: middle !important;
                                                                text-align: center !important;
                                                            }
                                                        </style>
                                                        <?php
                                                        include("connect.php");

                                                        // เชื่อมกับ request product
                                                        // $query = "SELECT quatation.*,request_product.* FROM quatation LEFT JOIN request_product ON quatation.num_req = request_product.num_req";

                                                        if ($EmployeeCode == 'xxxxx') {
                                                            $query = "SELECT quatation.*,status.*, IsNull((Select Top 1 reqp.product From request_product reqp Where reqp.num_req = quatation.num_req), N'') As product_name  
                                                            FROM quatation LEFT JOIN status ON quatation.status = status.status_id  ORDER BY date_time_stamp DESC ";
                                                        } else {
                                                            $query = "SELECT quatation.*,status.*, IsNull((Select Top 1 reqp.product From request_product reqp Where reqp.num_req = quatation.num_req), N'') As product_name 
                                                            FROM quatation LEFT JOIN status ON quatation.status = status.status_id 
                                                            where (quatation.pu_code = '$EmployeeCode' 
                                                            OR quatation.approver_user_code = '$EmployeeCode' 
                                                            OR quatation.employee_code_request = '$EmployeeCode' 
                                                            OR quatation.approver_gm_code = '$EmployeeCode' 
                                                            OR quatation.approver_md_code = '$EmployeeCode' 
                                                            OR quatation.approver_pu_code = '$EmployeeCode') 
                                                            ORDER BY date_time_stamp DESC ";
                                                        }
                                                        $returnedValue = sqlsrv_query($conn, $query);
                                                        $row = sqlsrv_fetch_array($returnedValue, SQLSRV_FETCH_ASSOC);
                                                        // $query = sqlsrv_query($conn, "SET NAMES UTF8");
                                                        // $query = sqlsrv_query($conn, $strSQL);

                                                        $count_record = 0;

                                                        if ($row === false) {
                                                            // echo "Error while fetching array.\n";
                                                            die(print_r(sqlsrv_errors(), true));
                                                        } else if ($row === null) {
                                                            echo "No results were found.\n";
                                                        } else {
                                                            do {
                                                                $count_record++;


                                                        ?>
                                                                <tr>

                                                                    <td><?php if ($row["status"] == 1) { ?>
                                                                            <button style="font-size:12px;" class="btn btn-secondary btn-block " id="btnControlFont"><i class="fa fa-commenting" aria-hidden="true"></i> <?php echo $row["status_name"]; ?><br>

                                                                                <div class="stepper-wrapper">
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>

                                                                                    </div>
                                                                                    <div class="stepper-item active">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item ">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                    <br>
                                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">15%</div>
                                                                                </div>
                                                                            </button>

                                                                            <?php } elseif ($row["status"] == 2) { ?>
                                                                            <button style="font-size:12px;" class="btn btn-warning btn-block " id="btnControlFont"><i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo $row["status_name"]; ?><br>
                                                                                <div class="stepper-wrapper">
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item active">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                    <br>
                                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">30%</div>
                                                                                </div>
                                                                            </button>
                                                                            
                                                                            <?php } elseif ($row["status"] == 9) { ?>
                                                                            <button style="font-size:12px;" class="btn btn-warning btn-block " id="btnControlFont"><i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo $row["status_name"]; ?><br>
                                                                                <div class="stepper-wrapper">
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item active">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                    <br>
                                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">45%</div>
                                                                                </div>
                                                                            </button>
                                                                            
                                                                            <?php } elseif ($row["status"] == 10) { ?>
                                                                            <button style="font-size:12px;" class="btn btn-warning btn-block " id="btnControlFont"><i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo $row["status_name"]; ?><br>
                                                                                <div class="stepper-wrapper">
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item active">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                    <br>
                                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">60%</div>
                                                                                </div>
                                                                            </button>
                                                                            
                                                                        <?php } elseif ($row["status"] == 3) { ?>
                                                                            <button style="font-size:12px;" class="btn btn-warning btn-block " id="btnControlFont"><i class=" fa fa-check-circle" aria-hidden="true"></i> <?php echo $row["status_name"]; ?><br><?php echo $row["pu_nameTH"]; ?>
                                                                                <div class="stepper-wrapper">
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>                                                                                    
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>                                                                                    
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item active">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                    <br>
                                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                                                                                </div>
                                                                            </button>

                                                                        <?php } elseif ($row["status"] == 4) { ?>
                                                                            <button style="font-size:12px;" class="btn btn-success btn-block " id="btnControlFont"><i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo $row["status_name"]; ?> <br><?php echo $row["pu_nameTH"]; ?>
                                                                                <div class="stepper-wrapper">
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                    <br>
                                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%; " aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
                                                                                </div>
                                                                            </button>

                                                                            <?php } elseif ($row["status"] == 5) {
                                                                            if ($row["work_process_status_user"] == 'unsuccess') { ?>
                                                                                <button style="font-size:12px;" class="btn btn-danger btn-block " id="btnControlFont"><i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo $row["status_name"]; ?> UnApproved by <?php echo $row["approver_user_nameTH"]; ?> <br>
                                                                                    <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                        <br>
                                                                                        <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">15%</div>
                                                                                    </div>
                                                                                </button>

                                                                            <?php   } elseif ($row["work_process_status_approvepu"] == 'unsuccess') { ?>
                                                                                <button style="font-size:12px;" class="btn btn-danger btn-block " id="btnControlFont"><i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo $row["status_name"]; ?> UnApproved by <?php echo $row["approver_pu_nameTH"]; ?> <br>
                                                                                    <div class="stepper-wrapper">
                                                                                        <div class="stepper-item completed">
                                                                                            <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                        </div>
                                                                                        <div class="stepper-item ">
                                                                                            <div class="step-counter"></div>
                                                                                        </div>
                                                                                        <div class="stepper-item ">
                                                                                            <div class="step-counter"></div>
                                                                                        </div>
                                                                                        <div class="stepper-item ">
                                                                                            <div class="step-counter"></div>
                                                                                        </div>
                                                                                        <div class="stepper-item ">
                                                                                            <div class="step-counter"></div>
                                                                                        </div>
                                                                                        <div class="stepper-item ">
                                                                                            <div class="step-counter"></div>
                                                                                        </div>
                                                                                        <div class="stepper-item ">
                                                                                            <div class="step-counter"></div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                        <br>
                                                                                        <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">15%</div>
                                                                                    </div>
                                                                                </button>

                                                                            <?php   } elseif ($row["work_process_status_pu"] == 'unsuccess') { ?>
                                                                                <button style="font-size:12px;" class="btn btn-danger btn-block " id="btnControlFont"><i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo $row["status_name"]; ?> UnApproved by <?php echo $row["pu_nameTH"]; ?> <br>
                                                                                    <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                        <br>
                                                                                        <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                                                                                    </div>
                                                                                </button>

                                                                            <?php   } else { ?>
                                                                                <button style="font-size:12px;" class="btn btn-danger btn-block " id="btnControlFont"><i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo $row["status_name"]; ?><br>
                                                                                    <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                        <br>
                                                                                        <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
                                                                                    </div>
                                                                                </button>
                                                                            <?php    } ?>

                                                                        <?php } elseif ($row["status"] == 6 || $row["status"] == 11 || $row["status"] == 12) { ?>
                                                                            <button style="font-size:12px;" class="btn btn-info btn-block " id="btnControlFont"><i class="fa fa-retweet" aria-hidden="true"></i> <?php echo $row["status_name"]; ?><br>
                                                                                <div class="stepper-wrapper">
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item active">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item ">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item ">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item ">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item ">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                    <br>
                                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">15%</div>
                                                                                </div>
                                                                            </button>

                                                                        <?php } elseif ($row["status"] == 7) { ?>
                                                                            <button style="font-size:12px;" class="btn btn-info btn-block " id="btnControlFont"><i class="fa fa-retweet" aria-hidden="true"></i> <?php echo $row["status_name"]; ?><br><?php echo $row["pu_nameTH"]; ?>
                                                                                <div class="stepper-wrapper">
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item active">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                    <div class="stepper-item ">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                    <br>
                                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                                                                                </div>
                                                                            </button>

                                                                        <?php } elseif ($row["status"] == 8) { ?>
                                                                            <button style="font-size:12px;" class="btn btn-info btn-block " id="btnControlFont"><i class="fa fa-retweet" aria-hidden="true"></i> <?php echo $row["status_name"]; ?><br><?php echo $row["pu_nameTH"]; ?>
                                                                                <div class="stepper-wrapper">
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item completed">
                                                                                        <div class="step-counter"><i class="fa fa-check" aria-hidden="true" style="font-size: 10px;"></i></div>
                                                                                    </div>
                                                                                    <div class="stepper-item active">
                                                                                        <div class="step-counter"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="progress" style="margin-top:5px;height: 10px;">
                                                                                    <br>
                                                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">90%</div>
                                                                                </div>
                                                                            </button>
                                                                        <?php    } ?>
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        <!-- ============================== ยังไม่มีเงื่อนไข Add fucntion by 04726 20260402==================================================== -->
                                                                       <?php
                                                                        $rfqNum = $row['num_req'];
                                                                        ?>

                                                                        <?php if (isset($rfqStarted[$rfqNum])): ?>

                                                                            <?php $endDate = $rfqStarted[$rfqNum]['EndDate_LT']; ?>

                                                                            <?php if ($endDate == null): ?>
                                                                                <a class="btn btn-warning btn-xs" title="Searching">
                                                                                    <i class="fa fa-clock text-white"></i>
                                                                                </a>
                                                                            <?php else: ?>
                                                                                <a class="btn btn-success btn-xs" title="Completed <?php echo $endDate->format('d/m/Y H:i'); ?>">
                                                                                    <i class="fa fa-clock text-white"></i>
                                                                                </a>
                                                                            <?php endif; ?>

                                                                        <?php endif; ?>
                                                                            <!-- ================================================================================== --> 
                                                                        <a href="Quatation_edit.php?quatation_ID=<?php echo $row["quatation_ID"]; ?>&rfq=<?php echo $row["num_req"]; ?>"
                                                                        class="btn btn-primary">
                                                                        <i class="fa fa-pencil-square-o"></i>
                                                                        </a>
                                                                        <!-- <a data-toggle="tooltip" data-placement="right" title="Edit Quotation <?php echo $row["num_req"] ?>" href="Quatation_edit.php?quatation_ID=<?php echo $row["quatation_ID"]; ?>" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> -->
                                                                        <?php if ($statusAdmin == 'yes') { ?>
                                                                            <a data-toggle="tooltip" data-placement="right" title="Delete Quotation <?php echo $row["num_req"] ?>" href="db_delete_quatation.php?quatation_ID=<?php echo $row["quatation_ID"]; ?>&num_req=<?php echo $row["num_req"]; ?>&EmployeeCode&<?php echo $EmployeeCode; ?>EmployeeNameTH=<?php echo $EmployeeThFirstName . ' ' . $EmployeeThLastName; ?>" onclick="return confirm('Are you sure to delete >> <?php echo $row['num_req']; ?> << ?')" class="btn btn-danger  btn-xs"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                                        <?php } ?>

                                                                        
                                                                        
                                                                    </td>
                                                                    <td><?php echo $count_record; ?></td>
                                                                    <td><?php echo $row["num_req"]; ?></td>
                                                                    <td><?php echo $row["product_name"]; ?></td>
                                                                    <td><?php echo $row["name_request"]; ?></td>
                                                                    <td><?php echo $row["employee_code_request"]; ?></td>
                                                                    <td><?php echo $row["department"]; ?></td>
                                                                    <td><?php echo $row["tel"]; ?></td>
                                                                    <td><?php echo $row["email"]; ?></td>

                                                                    <td><?php echo (is_null($row["date_picker"])) ? '' : date_format($row["date_picker"], "d/m/Y H:i:s"); ?></td>
                                                                    <td><?php echo date_format($row["date_time_stamp"], "d/m/Y H:i:s"); ?></td>
                                                                    <td><?php echo date_format($row["date_time_stamp_update"], "d/m/Y H:i:s"); ?></td>


                                                                </tr>
                                                        <?php
                                                            } while ($row = sqlsrv_fetch_array($returnedValue, SQLSRV_FETCH_ASSOC));
                                                        }
                                                        ?>
                                                    </tbody>

                                                    </ะ>
                                                </table>
                                                <!-- </div> -->
                                                <!-- END DATA TABLE-->
                                                <!-- </div> -->
                                            </div>
                                            <!-- </div> -->
                                            <!-- </div> -->

                                        </div>
                                    </div>
                                    <!-- </div> -->
                                </div>
                                <!-- END MAIN CONTENT-->
                                <!-- END PAGE CONTAINER-->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>







    <div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Confirm Submit
                </div>
                <div class="modal-body">
                    Are you sure you want to submit ?

                    <!-- We display the details entered by the user here -->


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a type="submit" id="submit" class="btn btn-success success">Submit</a>
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

    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/chartjs/chart.js"></script>


    <!-- Main JS-->
    <script src="js/main.js"></script>


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




</body>

</html>




<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    });


    $(document).ready(function() {
        pdfMake.fonts = {

            THSarabun: {
                normal: 'THSarabun.ttf',
                bold: 'THSarabun-Bold.ttf',
                italics: 'THSarabun-Italic.ttf',
                bolditalics: 'THSarabun-BoldItalic.ttf'

            }
        }


        $.extend(true, $.fn.dataTable.defaults, {
            "language": {
                "sProcessing": "กำลังดำเนินการ...",
                "sLengthMenu": "แสดง_MENU_ แถว",
                "sZeroRecords": "ไม่พบข้อมูล",
                "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
                "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 แถว",
                "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
                "sInfoPostFix": "",
                "sSearch": "ค้นหา:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "เริ่มต้น",
                    "sPrevious": "ก่อนหน้า",
                    "sNext": "ถัดไป",
                    "sLast": "สุดท้าย"
                }
            }
        });


        $('#example1').DataTable({

            order: [
                [2, 'asc']
            ],

            dom: 'lfB<t>ip',
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50],

            buttons: [{

                extend: 'collection',
                text: 'Export  <i class="fa fa-files-o" style="margin-left:10px;"></i>',
                className: 'custom-html-collection',
                buttons: [
                    '<h3>Export</h3>',

                    'excel',

                ]
            }, ],

            // info: false,

            // fixedHeader: true,
            scrollX: true,
            // responsive: true,
            deferRender: true,


        });
        // $('#example td').css('white-space', 'initial');


    });



    $('.dt-buttons').removeClass('dt-buttons').addClass("dt-buttons").css({
        "position": "fixed"
    });
</script>

<script>
    const data = {
        labels: [
            'Success',
            'Inprocess',
            'Unsuccess',
        ],
        datasets: [{
            label: 'My First Dataset',
            data: [<?php echo $rowcountSuccess ?>, <?php echo $rowcountInprocess ?>, <?php echo $rowcountUnsuccess ?>],

            backgroundColor: [
                '#00b26f',
                '#ff8300',
                '#fa4251',
            ],
            hoverOffset: 4,
            borderAlign: 'center',
        }],
    };

    const config = {
        type: 'doughnut',
        data: data,
    };
</script>

<script>
    const ctx2 = document.getElementById('myChart3');
    ctx2.height = 200;

    const myChart3 = new Chart(ctx2, {

        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August ', 'September', 'October', 'November', 'December'],
            datasets: [{
                    type: 'line',
                    label: 'Unsuccess',

                    fill: false,
                    data: [<?php echo $count_job_unsuccess_month[0] ?>,
                        <?php echo $count_job_unsuccess_month[1] ?>,
                        <?php echo $count_job_unsuccess_month[2] ?>,
                        <?php echo $count_job_unsuccess_month[3] ?>,
                        <?php echo $count_job_unsuccess_month[4] ?>,
                        <?php echo $count_job_unsuccess_month[5] ?>,
                        <?php echo $count_job_unsuccess_month[6] ?>,
                        <?php echo $count_job_unsuccess_month[7] ?>,
                        <?php echo $count_job_unsuccess_month[8] ?>,
                        <?php echo $count_job_unsuccess_month[9] ?>,
                        <?php echo $count_job_unsuccess_month[10] ?>,
                        <?php echo $count_job_unsuccess_month[11] ?>
                    ],

                    borderColor: '#fa4251',
                    // tension: 1,
                    fill: false,

                }, {
                    type: 'line',
                    label: 'success',
                    fill: false,
                    data: [<?php echo $count_job_success_month[0] ?>,
                        <?php echo $count_job_success_month[1] ?>,
                        <?php echo $count_job_success_month[2] ?>,
                        <?php echo $count_job_success_month[3] ?>,
                        <?php echo $count_job_success_month[4] ?>,
                        <?php echo $count_job_success_month[5] ?>,
                        <?php echo $count_job_success_month[6] ?>,
                        <?php echo $count_job_success_month[7] ?>,
                        <?php echo $count_job_success_month[8] ?>,
                        <?php echo $count_job_success_month[9] ?>,
                        <?php echo $count_job_success_month[10] ?>,
                        <?php echo $count_job_success_month[11] ?>
                    ],
                    borderColor: '#00b26f',
                    // tension: 1,
                    fill: false,
                },
                {
                    type: 'line',
                    label: 'Inprocess',
                    fill: false,
                    data: [<?php echo $count_job_inprocess_month[0] ?>,
                        <?php echo $count_job_inprocess_month[1] ?>,
                        <?php echo $count_job_inprocess_month[2] ?>,
                        <?php echo $count_job_inprocess_month[3] ?>,
                        <?php echo $count_job_inprocess_month[4] ?>,
                        <?php echo $count_job_inprocess_month[5] ?>,
                        <?php echo $count_job_inprocess_month[6] ?>,
                        <?php echo $count_job_inprocess_month[7] ?>,
                        <?php echo $count_job_inprocess_month[8] ?>,
                        <?php echo $count_job_inprocess_month[9] ?>,
                        <?php echo $count_job_inprocess_month[10] ?>,
                        <?php echo $count_job_inprocess_month[11] ?>
                    ],
                    borderColor: '#ff8300',
                    // tension: 1,
                    fill: false,
                }

            ]
        },

        options: {



            transitions: {
                show: {
                    animations: {
                        x: {
                            from: 0
                        },
                        y: {
                            from: 0
                        }
                    }
                },
                hide: {
                    animations: {
                        x: {
                            to: 0
                        },
                        y: {
                            to: 0
                        }
                    }
                }
            }
        },





    });
</script>

<script>
    // === include 'setup' then 'config' above ===

    const myChart2 = new Chart(
        document.getElementById('myChart2'),
        config
    );
</script>

<script type="text/javascript">
    // เพิ่มส่วนนี้เข้าไปจะถือว่าเป็นการตั้งค่าให้ Datatable เป็น Default ใหม่เลย



    // เรียกใช้งาน Datatable function
</script>


<script script type="text/javascript" charset="utf8" src="js/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="js/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="js/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="js/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="js/buttons.print.min.js"></script>
<script type="text/javascript" charset="utf8" src="js/buttons.colVis.min.js"></script>
<script type="text/javascript" charset="utf8" src="js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" charset="utf8" src="js/dataTables.bootstrap4.min.js"></script>


<script src="js/swallow.js"></script>
<!-- end document-->
<?php sqlsrv_close($conn); ?>