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

include("connect.php");

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

$sqlAdmin = "SELECT * FROM admin_purchase where EmployeeCode = '$EmployeeCode' and Type = 'Admin' ";
$queryAdmin = sqlsrv_query($conn, $sqlAdmin);
$resultAdmin = sqlsrv_fetch_array($queryAdmin, SQLSRV_FETCH_ASSOC);
if (!$resultAdmin) {
   $statusAdmin = 'no';
} else if ($resultAdmin === null) {
   echo "No results were found.\n";
   $statusAdmin = 'no';
} else {
   $statusAdmin = 'yes';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Purchase</title>
    <link href="css/bootstrap5.0.1.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">

    <style>
    form {
        margin: 20px 0;
    }

    form input,
    button {
        padding: 5px;
    }



    /* .dataTables_scroll {
         overflow: auto;
      } */

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

    .hidden {
        display: none;
    }




    table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 0.1px solid #cdcdcd;
    }

    table th,
    table td {
        padding: 10px;
        text-align: left;
    }


    .dataTables_empty {
        text-transform: uppercase;
        background-image: linear-gradient(-225deg,
                #232f34 0%,
                #344955 29%,
                #d39e00 67%,
                #fff800 100%);
        background-size: auto auto;
        background-clip: border-box;
        background-size: 200% auto;
        color: #fff;
        background-clip: text;

        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: textclip 2s linear infinite;
        display: inline-block;
        font-size: 25px;
    }


    @keyframes textclip {
        to {
            background-position: 200% center;
        }
    }


    .dt-button-collection {

        overflow-y: scroll;
        max-height: 500px;
    }

    .btn-group {
        float: right !important;

    }

    .dataTables_filter {
        float: left !important;
    }

    .dataTables_processing {
        position: absolute;
        top: 40% !important;
        left: 50% !important;
        width: 100%;
        height: 100px;
        margin-left: -50%;
        margin-top: 0px !important;
        padding: 20px !important;
        /* padding-top: 20px; */
        text-align: center;
        /* font-size: 1.2em; */
        background-color: white;
        z-index: 999;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);


    }



    table.dataTable tbody>tr.selected,
    table.dataTable tbody>tr>.selected {
        background-color: #6c757d !important;
    }

    table.dataTable tbody tr.selected a,
    table.dataTable tbody th.selected a,
    table.dataTable tbody td.selected a {
        color: #ffffff !important;
    }

    .dataTables_scrollHeadInner,
    .table {
        width: 100% !important;
    }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

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
    <!-- <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all"> -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="all">
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

    <!-- Include Bootstrap Datepicker -->
    <link rel="stylesheet" href="css/font-awesome.css" />
    <link rel="stylesheet" href="css/bootstrap-datepicker.min.css" />
    <script src="js/bootstrap-datepicker.min.js"></script>


</head>

<style type="text/css">
.btnAdd {
    text-align: right;
    width: 83%;
    margin-bottom: 20px;
}
</style>

<!-- <body class="animsition"> -->

<body onload="myFunction()" style="margin:0;background-image: url('images/imageedit_1_5977293344.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: 100% 110%;">

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

                                <h1 style="color: white;"><i class=" fa fa-file-text" aria-hidden="true"></i> Request
                                    for quotation</h1> <!-- <img src="images/icon/logo.png" alt="CoolAdmin" /> -->
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
                                    <i class="fas fa-align-justify"></i>Add Quotation</a>
                            </li>


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
                            <li>
                                <a href="Quatation.php">
                                    <i class="fas fa-align-justify"></i>Quotation</a>
                            </li>

                            <li class="active has-sub">
                                <a href="#">
                                    <i class="fas fa-lock"></i>Edit Admin</a>
                            </li>

                            <?php 
                            $token = base64_encode($EmployeeCode); 
                            ?>

                                <li>
                                    <a href="https://web.ts-engineering.com/TSE_Upload_RFQ/home/category?token=<?php echo $token; ?>" target="_blank">
                                        <i class="fa fa-user"></i> Category
                                    </a>
                                </li>

                                <li>
                                    <a href="https://web.ts-engineering.com/TSE_Upload_RFQ/home/upload?token=<?php echo $token; ?>" target="_blank">
                                        <i class="fa fa-upload"></i> Upload
                                    </a>
                                </li>

                                <li>
                                    <a href="https://web.ts-engineering.com/TSE_Upload_RFQ/home/Search?token=<?php echo $token; ?>" target="_blank">
                                        <i class="fa fa-search" aria-hidden="true"></i> Search
                                    </a>
                                </li>

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
            <div class="page-container" style="background-color:transparent; ">
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



                            <!-- <script>
                        $(document).ready(function() {
                           $("#flash-msg").delay(1500).fadeIn(800).delay(20000).fadeOut("slow");

                        });
                     </script> -->

                            <div class="alert alert-success alert-dismissible " aria-hidden="true"
                                id="flash-msg-UpdateSuccess"
                                style="display:none; z-index:999; position: fixed; top: 1em; right: 1em; width: 75%; margin-right:-2000px;"
                                role="alert">
                                <strong>Update Successfully !</strong> Data have been update now.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>

                            <div class="alert alert-danger alert-dismissible " aria-hidden="true"
                                id="flash-msg-UnSuccess"
                                style="display:none; z-index:999; position: fixed; top: 1em; right: 1em; width: 75%; margin-right:-2000px;"
                                role="alert">
                                <strong>Error !</strong> An unexpected error has occurred.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>

                            <div class="alert alert-success alert-dismissible " aria-hidden="true"
                                id="flash-msg-AddSuccess"
                                style="display:none; z-index:999; position: fixed; top: 1em; right: 1em; width: 75%; margin-right:-2000px;"
                                role="alert">
                                <strong>Data added Successfully !</strong> Data has been added to your table.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>

                            <div class="alert alert-success alert-dismissible " aria-hidden="true"
                                id="flash-msg-DeleteSuccess"
                                style="display:none; z-index:999; position: fixed; top: 1em; right: 1em; width: 75%; margin-right:-2000px;"
                                role="alert">
                                <strong>Delete data Successfully !</strong> Data has been delete from your table.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>





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
                                    <h2 class="m-b-20" style="color: white;">Admin Page</h2>
                                    <div class="top-campaign"
                                        style="padding-bottom: 45px; background-color: rgba(255,255,255, 0.95);border-radius:5px">

                                        <div class="row">


                                            <div class="col-lg-12">




                                                <div class="card-title">
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-4"></div>
                                                        <div class="col-md-6 col-sm-4">
                                                            <h3 class="text-center title-10">Data Table</h3>

                                                        </div>

                                                    </div>

                                                    <hr>

                                                    <div class="row" style="margin-bottom: 10px;">



                                                        <div class="col-md-3 col-sm-6">
                                                            <?php if ($statusAdmin == 'yes') { ?>
                                                            <button data-id="" data-bs-toggle="modal"
                                                                data-bs-target="#addUserModal"
                                                                class="btn btn-success btn-block"> <i
                                                                    class="fa fa-plus"></i> Add Row</button>
                                                            <?php } ?>
                                                        </div>

                                                        <div class="col-md-6 col-sm-6"></div>
                                                        <div class="col-md-3 col-sm-6">

                                                            <button type="button" id="btn-reload" class="btn btn-success
                                                         btn-block"> <i class="fa fa-refresh"></i> Reload</button>

                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="table-responsive">
                                                                <!-- <table id="example" class="display" style="width:100% "> -->
                                                                <table id="example"
                                                                    class="table table-bordered table-striped table-hover"
                                                                    cellspacing="0" style="width:100% ">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>ID</th>

                                                                            <th class="Options">Options</th>

                                                                            <th>EmployeeCode</th>
                                                                            <th>ThFullName</th>
                                                                            <th>PlantNameTH</th>
                                                                            <th>SubLevelNo</th>
                                                                            <th>Type</th>
                                                                        </tr>
                                                                    </thead>


                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>





                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>




                            </div>

                            <!-- END โชว์ alert -->


                            <div class="loader">
                                <div class="row">
                                    <div class="loading">
                                    </div>
                                </div>
                                <div class="row">
                                    <div
                                        style="margin:auto ; padding:10; justify-content: center; align-items: center;">
                                        <text style="text-align: center; color: #ffffff; font-weight: bold; "><span
                                                id="typed"></span></text>

                                    </div>

                                </div>
                            </div>






                        </div>
                    </div>













                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->

    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
    <!-- <link rel="stylesheet" type="text/css" href="css/buttons.dataTables.min.css"> -->
    <link rel="stylesheet" type="text/css" href="css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="css/fixedHeader.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="css/select.bootstrap5.min.css">



    <!--  https://cdn.datatables.net/v/dt/dt-1.10.15/datatables.min.css -->
    <!-- <link rel="stylesheet" type="text/css" href="css/datatables.min.css"> -->
    <!--  https://cdn.datatables.net/v/dt/dt-1.10.15/datatables.min.css -->

    <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/dataTables.bootstrap5.min.js"></script>
    <!-- <script type="text/javascript" src="js/fixedHeader.bootstrap5.min.js"></script>
     -->
    <script type="text/javascript" src="js/dataTables.fixedHeader.min.js"></script>
    <script type="text/javascript" src="js/dataTables.select.min.js"></script>

    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script> -->
    <!-- <script type="text/javascript" src="js/dt-1.10.15/datatables.min.js"></script> -->
    <!-- 1.11.5/js -->


    <script type="text/javascript" charset="utf8" src="js/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf8" src="js/dataTables.buttons.min.js"></script>

    <script type="text/javascript" charset="utf8" src="js/buttons.bootstrap5.min.js"></script>


    <script type="text/javascript" charset="utf8" src="js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/buttons.print.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/buttons.colVis.min.js"></script>
    <script type="text/javascript" charset="utf8" src="js/jquery.mockjax.min.js"></script>


    <?php
                                       } while ($resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC));
                                    }
?>




    <!-- Add user Modal -->
    <!-- <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <form id="addUser" action="">
         <div class="modal-content">
            <div class="loader">
               <div class="row">
                  <div class="loading">
                  </div>
               </div>
               <div class="row">
                  <div style="margin:auto; padding:10; justify-content: center; align-items: center;">
                     <text style="text-align: center;color: #ffffff; font-weight: bold; "><span id="typed"></span></text>

                  </div>

               </div>



            </div>

            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Add New Admin</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

               <input type="hidden" name="name_request" id="name_request" value="<?php //echo  $name_request ?>">


               <div class="input-group mb-3">
                  <div class="input-group-prepend">
                     <label class="input-group-text" for="pu_code">ผู้ดูแล</label>
                  </div>
                  <select class="custom-select" id="pu_code" name="pu_code" required>

                     <option value="">..กรุณาเลือกผู้ดูแล..</option>
                     <?php


                     // $sqlPurchase = "SELECT * FROM vw_Employee where DivisionCode = '1600414000' and EmployeeStatusCode = '01'";
                     // $queryPurchase = sqlsrv_query($conn, $sqlPurchase);
                     // $resultPurchase = sqlsrv_fetch_array($queryPurchase, SQLSRV_FETCH_ASSOC);
                     // if (!$resultPurchase) {
                     //    // echo "Error while fetching array.\n";
                     //    die(print_r(sqlsrv_errors(), true));
                     // } else if ($resultPurchase === null) {
                     //    echo "No results were found.\n";
                     // } else {
                     //    do {
                     ?>

                           <option value="<?php //echo $resultPurchase["EmployeeCode"]; ?>">
                              <?php //echo $resultPurchase["ThFullName"]; ?> </option>
                     <?php
                     //    } while ($resultPurchase = sqlsrv_fetch_array($queryPurchase, SQLSRV_FETCH_ASSOC));
                     // }
                     ?>
                     <option value="1100528">Super Admin</option>
                  </select>
               </div>




            </div>
            <div class="modal-footer">
               <div class="text-center">
                  <button type="submit" class="btn btn-primary btn-success btn-block" onclick="return confirm('ยืนยันการบันทึก')"><i class="fa fa-save"></i> Save</button>
               </div>
               <button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
         </div>
      </form>
   </div>
</div> -->













    <div class="modal fade" id="addUserModal">
        <div class="modal-dialog modal-md">
            <div class=" modal-content">

                <div class="modal-header ">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add New Data</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
                    <!-- <span aria-hidden="true">&times;</span> -->
                    </button>
                </div>
                <div class="modal-body">



                    <form style="margin-bottom: 0px;" action="" name="searchData" id="searchData" method="post"
                        ENCTYPE="multipart/form-data">


                        <div class="row">
                            <div class="col-12">
                                <label for="addEmployeeID"><i class="fas fa-barcode"></i> Employee ID</label>
                                <div class="input-group mb-3">

                                    <input type="text" class="form-control" id="addEmployeeID" name="addEmployeeID"
                                        placeholder="Enter EmployeeID" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i id="fa-search"
                                                class="fas fa-search"></i><i id="fa-spin"
                                                class="fas fa-circle-notch fa-spin" style="display: none;"></i>
                                            Search</button>
                                    </div>
                                </div>

                                <!-- <div class="form-group" id="plantInput" style="display: none;"> -->
                                <div class="form-group" id="employeeSearch" style="display: none;">
                                    <label for="addThFullName"><i class="fa fa-tags"></i> ThFullName</label>
                                    <input type="text" class="form-control" id="addThFullName" name="addThFullName"
                                        disabled>
                                </div>

                                <div class="form-group" id="employeeSearch1" style="display: none;">
                                    <label for="addPlantNameTH"><i class="fa fa-building"></i> PlantNameTH</label>
                                    <input type="text" class="form-control" id="addPlantNameTH" name="addPlantNameTH"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form style="margin-top: 0px;" action="" id="addUser" name="addUser" method="post"
                        ENCTYPE="multipart/form-data">

                        <input type="hidden" name="employeeID_for_add" id="employeeID_for_add" required>

                        <!-- 
               <div class="row" style="display: none;" id="typeSelect">
                  <div class="col-md-12">
                     <label class="form-select"><i class="fas fa-user"></i>Type</label>
                     <div class="input-group mb-3">
                        <div class="input-group-prepend">
                           <label class="input-group-text" for="addType">ผู้จัดซื้อ</label>
                        </div>

                        <select class="custom-select" id="addType" name="addType" style="width: 100%;" required>
                           <option selected="selected" value="">Please Select Type . . .</option>
                           <option value="Admin">Admin</option>
                           <option value="Supervisor">Supervisor</option>

                        </select>

                     </div>
                  </div>
               </div> -->

                        <div class="row" style="display: none;" id="typeSelect">
                            <div class="col-md-12">
                                <label for="addType"><i class="fas fa-user"></i> Type</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="addType">Select Type</label>
                                    </div>
                                    <select class="custom-select" id="addType" name="addType" required>

                                        <option selected="selected" value="">Please Select Type . . .</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Approver">Approver</option>
                                        <option value="Staff">Staff</option>


                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="row" style="margin-top: 10px;">
                            <div class="col-12">
                                <!-- <button type="submit" class="btn btn-primary btn-success btn-block" onclick="return confirm('ยืนยันการบันทึก')"><i class="fa fa-save"></i> Save</button> -->
                                <button type="submit" class="btn btn-primary btn-success btn-block" id="submitData"
                                    disabled='disabled'><i class="fa fa-save"></i> Save Change</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default " data-dismiss="modal">Close</button> -->
                    <button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><i class="fa fa-times"></i>
                        Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Jquery JS-->
    <!-- <script src="vendor/jquery-3.2.1.min.js"></script> -->
    <!-- Bootstrap JS-->
    <!-- <script src="vendor/bootstrap-4.1/popper.min.js"></script>
<script src="vendor/bootstrap-4.1/bootstrap.min.js"></script> -->
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











    <script type="text/javascript">
    function isNumeric(n) {
        return /^-?[\d.]+(?:e-?\d+)?$/.test(n);
    }

    $(document).ready(function() {
        console.log('statusAdmin = ' + '<?php echo $statusAdmin; ?>');


        var table = $('#example').DataTable({
            "createdRow": function(nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[0]);
            },

            // "bSortClasses": 'false',
            // "processing": 'true',
            // "paging": 'true',
            // "pageLength": 10,
            // "serverSide": 'true',
            // "ajax": "fetch_data.php",
            // 'scrollX': true,
            // 'scrollX': true,
            // "serverSide": true,
            'select': 'true',
            'orderClasses': 'false',
            // 'fixedHeader': 'true',
            'processing': 'true',
            // "serverSide": true,
            'bDeferRender': 'true',
            'language': {
                'loadingRecords': 'loading...',
                'processing': '<div><div class="row"> <div style="margin-left: auto;margin-right: auto;z-index: 999;" class="spinner-border text-warning" role="status"></div></div><div class="row"><div style="margin:auto ; padding:10; justify-content: center; align-items: center;"> <text style="text-align: center; color: #000000; font-weight: bold; "><span id="typed"></span></text></div></div></div>'

            },
            'paging': 'true',
            'order': [],
            'ajax': {
                'url': 'fetch_data.php',
                'type': 'post',
            },

            "deferRender": true,
            "aoColumnDefs": [{
                "defaultContent": "",
                "targets": "_all",
                "bSortable": true,
                // "aTargets": [22],

            }, ],
            "columnDefs": [{
                "targets": [6],
                "class": "text-center",
                "width": '70px'
            }],
            "dom": '<lf>BrtipTS',
            buttons: [
                'colvis'
            ],
            // "scrollY": "200px",
            // "scrollCollapse": true,
            // "paging": false

            initComplete: function() {


                if ('<?php echo $statusAdmin; ?>' === 'no') {
                    // Hide Office column
                    var api = this.api();
                    api.column(1).visible(false);
                }
            }

        });


        table.columns.adjust().draw();




        $('#btn-reload').on('click', function() {
            table.ajax.reload(null, false);
        });



    });


    $(document).on('submit', '#addUser', function(e) {

        e.preventDefault();
        // var pu_code = $('#pu_code').val();
        var employeeID_for_add = $('#employeeID_for_add').val();
        var addType = $('#addType').val();
        console.log('addType = ' + addType);
        $.ajax({
            url: "add_user.php",
            type: "POST",
            data: {
                pu_code: employeeID_for_add,
                addType: addType,
            },

            success: function(data) {

                var json = JSON.parse(data);

                var status = json.status;

                if (status == 'success') {
                    // document.getElementsByClassName("loader")[0].style.display = "block";
                    table = $('#example').DataTable();
                    // table.draw(false);
                    // table.ajax.reload();

                    let newID = json.results[0].Admin_purchaseID;
                    let EmployeeCode = json.results[0].EmployeeCode;
                    let ThFullName = json.results[0].ThFullName;
                    let PlantNameTH = json.results[0].PlantNameTH;
                    let SubLevelNo = json.results[0].SubLevelNo;
                    let Type = json.results[0].Type;
                    var button = '<td><a href="#!"  data-id="' + newID +
                        '"  class="btn btn-danger btn-block btn-sm deleteBtn" );"><i class="fa fa-times"></i> delete</a></td>';


                    if (Type == 'Admin') {
                        var TypeBut = '<span class="btn btn-block btn-warning btn-sm ">' + Type +
                            '</span>';
                    } else if (Type == 'Approver') {
                        var TypeBut = '<span class="btn btn-block btn-primary btn-sm ">' + Type +
                            '</span>';
                    } else if (Type == 'staff') {
                        var TypeBut = '<span class="btn btn-block btn-success btn-sm ">' + Type +
                            '</span>';
                    } else {
                        var TypeBut = '<span class="btn btn-block btn-success btn-sm ">' + Type +
                            '</span>';
                    }

                    table.row.add([
                        newID, button, EmployeeCode, ThFullName, PlantNameTH, SubLevelNo,
                        TypeBut
                    ])

                    table.draw(false);
                    // table.ajax.reload();


                    // document.getElementsByClassName("loader")[0].style.display = "none";
                    $('#addUserModal').modal('hide');

                    $(document).ready(function() {
                        $("#flash-msg-AddSuccess").clearQueue();
                        $("#flash-msg-UnSuccess").clearQueue();
                        $("#flash-msg-DeleteSuccess").clearQueue();
                        $("#flash-msg-UpdateSuccess").clearQueue();
                    });

                    $(document).ready(function() {
                        $("#flash-msg-AddSuccess").show().animate({

                            marginRight: "-2000px"
                        }, 200).animate({

                            marginRight: "0px"
                        }, 200).delay(7000).fadeOut("slow");

                    });
                    // table.ajax.reload(null, false);
                    // $("#example").DataTable().page('last').draw('page');

                    document.getElementById('pu_code').value = ''



                } else {
                    alert(status);
                }
            }
        });



    });



    $(document).on('click', '.deleteBtn', function(event) {
        var table = $('#example').DataTable();
        event.preventDefault();
        var id = $(this).data('id');

        if (confirm("Are you sure want to delete this data ? ")) {
            $.ajax({
                url: "delete_user.php",
                data: {
                    id: id
                },
                type: "post",
                success: function(data) {
                    var json = JSON.parse(data);
                    status = json.status;

                    if (status == 'success') {

                        $("#" + id).closest('tr').empty();
                        $(document).ready(function() {
                            $("#flash-msg-AddSuccess").clearQueue();
                            $("#flash-msg-UnSuccess").clearQueue();
                            $("#flash-msg-DeleteSuccess").clearQueue();
                            $("#flash-msg-UpdateSuccess").clearQueue();
                        });

                        $(document).ready(function() {


                            $("#flash-msg-DeleteSuccess").show().animate({

                                marginRight: "-2000px"
                            }, 200).animate({

                                marginRight: "0px"
                            }, 200).delay(7000).fadeOut("slow");

                        });





                    } else {

                        $(document).ready(function() {


                            $("#flash-msg-UnSuccess").show().animate({

                                marginRight: "-2000px"
                            }, 200).animate({

                                marginRight: "0px"
                            }, 200).delay(7000).fadeOut("slow");

                        });
                        // alert('Failed');
                        return;
                    }
                }
            });
        } else {
            return null;
        }

    });



    $(document).on('submit', '#searchData', function(e) {
        e.preventDefault();
        $('#fa-search').hide();
        $('#fa-spin').show();
        $('#employeeSearch').hide();
        $('#employeeSearch1').hide();
        $('#addType').val('');
        // $('#addType').select2({
        //    theme: 'bootstrap4'
        // }).val('').trigger('change');
        $('#addPlant').select2({
            theme: 'bootstrap4'
        }).val('').trigger('change');
        $('#typeSelect').hide();
        $('#plantSelect').hide();


        var employeeID = $('#addEmployeeID').val();
        var id = $('#id').val();


        $('#employeeID_for_add').val(employeeID);


        var formData = new FormData($(this)[0]);

        formData.append('employeeID', employeeID);


        if (employeeID != '') {
            $.ajax({
                url: "search_data.php",
                type: "POST",

                data: formData,
                async: false,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                // },
                success: function(data) {
                    var json = JSON.parse(data);
                    var status = json.status;
                    var results = json.results;
                    if (status == 'success') {

                        table = $('#example').DataTable();
                        $('#addThFullName').val(results[0].ThFullName);
                        $('#addPlantNameTH').val(results[0].PlantNameTH);


                        $('#modal-edit').modal('hide');
                        setTimeout(function() {
                                $('#submitData').attr('disabled', false);
                                $('#addType').val('');
                                // $('#addType').select2({
                                //    theme: 'bootstrap4'
                                // }).val('').trigger('change');
                                $('#addPlant').select2({
                                    theme: 'bootstrap4'
                                }).val('').trigger('change');
                                // $('#addType option:first').prop('selected', true);
                                $('#typeSelect').show();
                                $('#plantSelect').show();

                                $('#fa-spin').hide();
                                $('#fa-search').show();
                                $('#employeeSearch').show();
                                $('#employeeSearch1').show();
                            }

                            , 1000);

                    } else {
                        setTimeout(function() {
                                $('#submitData').attr('disabled', true);
                                $('#typeSelect').hide();
                                $('#plantSelect').hide();
                                $('#addType').val('');
                                // $('#addType').select2({
                                //    theme: 'bootstrap4'
                                // }).val('').trigger('change');
                                $('#addPlant').select2({
                                    theme: 'bootstrap4'
                                }).val('').trigger('change');
                                // $('#addType option:first').prop('selected', true);
                                $('#fa-spin').hide();
                                $('#fa-search').show();

                                $('#addThFullName').val('');
                                $('#addPlantNameTH').val('');
                                Swal.fire(
                                    'Failed',
                                    'Employee Code is not found !',
                                    'error'
                                )
                            }

                            , 1000);

                    }
                }
            });


        } else {

            $('#employeeSearch').hide();
            $('#employeeSearch1').hide();
        }

    });
    </script>
    <script src="vendor/typedjs/typed.min.js"></script>

    <script>
   //  var myVar;

   //  function myFunction() {
   //      myVar = setTimeout(showPage, 1000);
   //  }

   //  function showPage() {
   //      document.getElementById("loader").style.display = "none";
   //      document.getElementById("myDiv").style.display = "block";
   //  }
    </script>
    
    <script>
    $(function() {
        $("#typed").typed({
            strings: [
                "Loading.",
                "Loading..",
                "Loading...",

            ],
            typeSpeed: 20,
            // time before typing starts
            // startDelay: 100,
            // backspacing speed
            // backSpeed: 20,
            // time before backspacing

            // loop
            loop: true,
            // shuffle: true
        })
    })
    </script>



</body>

</html>





<!-- end document-->
<?php sqlsrv_close($conn); ?>