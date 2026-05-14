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
// $Username = 'Teerapat.Chu';
// $Username = 'Sombat.Chi';

$DepartmentCode = $_SESSION["DepartmentCode"];
// $_SESSION["CostCenter"] = $resultT["CostCenter"];

// $DepartmentCode = '1610360000';
// $DepartmentCode = '1600300000';
include("connect.php");
$sqlT = "SELECT * FROM vw_Employee where EmployeeUsername = '$Username' ";
$queryT = sqlsrv_query($conn, $sqlT);
$resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC);
if (!$resultT) {
   // echo "Error while fetching array.\n";
   echo "<script>alert('ไม่มีผู้ใช้ในระบบ กรุณาติดต่อ Admin');</script>";

   die(print_r(sqlsrv_errors(), true));
} else if ($resultT === null) {
   echo "<script>alert('ไม่มีผู้ใช้ในระบบ กรุณาติดต่อ Admin');</script>";

   echo "No results were found.\n";
} else {
   do {
      $EmployeeCode = $resultT["EmployeeCode"];
      // $EmployeeCode = $resultT["EmployeeCode"];
      $EmployeeThFirstName = $resultT["ThFirstName"];
      $EmployeeThLastName = $resultT["ThLastName"];
      $EmployeePlantCode = $resultT["PlantCode"];
   } while ($resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC));
}
// $EmployeeCode = '1600646';
// $DepartmentCode = '1610360000';

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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
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

    .hidden {
        display: none;
    }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

    <script src="js/jquery-3.5.1.min.js"></script>
    <script>
    var ar_ext = ['pdf', 'gif', 'jpe', 'jpg']; // array with allowed extensions 

    function checkName(el, to, sbm) {
        // - coursesweb.net 
        // get the file name and split it to separe the extension 
        var name = el.value;
        var ar_name = name.split('.');

        // for IE - separe dir paths (\) from name 
        var ar_nm = ar_name[0].split('\\');
        for (var i = 0; i < ar_nm.length; i++) var nm = ar_nm[i];

        // add the name in 'to' 
        document.getElementById(to).value = nm;

        // check the file extension 
        var re = 0;
        for (var i = 0; i < ar_ext.length; i++) {
            if (ar_ext[i] == ar_name[1]) {
                re = 1;
                break;
            }
        }

        // if re is 1, the extension is in the allowed list 
        if (re == 1) {
            // enable submit 
            document.getElementById(sbm).disabled = false;
        } else {
            // delete the file name, disable Submit, Alert message 
            el.value = '';
            document.getElementById(sbm).disabled = true;
            alert('.' + ar_name[1] + ' is not an file type allowed for upload');
        }
    }



    // $(document).ready(function() {
    //     $(".add-row").click(function() {
    //         var product = $("#product").val();
    //         var amount = $("#amount").val();
    //         var unit = $("#unit").val();
    //         var price = $("#price").val();

    //         var markup = "<tr><td><input type='checkbox' name='record'></td><td>" + product + "</td><td>" + amount + "</td><td>" + unit + "</td><td>" + price + "</td></tr>";
    //         $("#table1 tbody").append(markup);
    //     });

    //     // Find and remove selected table rows
    //     $(".delete-row").click(function() {
    //         $("#table1 tbody").find('input[name="record"]').each(function() {
    //             if ($(this).is(":checked")) {
    //                 $(this).parents("tr").remove();
    //             }
    //         });
    //     });
    // });




    // function closeSnoAlertBox() {
    //    $("#snoAlertBox").show()
    // }



    $(document).ready(function() {
        var count = 1;
        $('#add').click(function() {
            count = count + 1;

            var html_code = "<tr id='row" + count + "'>";
            html_code += "<td contenteditable='true' class='product'></td>";
            html_code += "<td contenteditable='true' class='amount'></td>";
            html_code += "<td contenteditable='true' class='unit'></td>";
            html_code += "<td  class='price'></td>";
            html_code += "<td><button type='button' name='remove' data-row='row" + count +
                "' class='btn btn-danger btn-xs remove'><i class='fa fa-times' aria-hidden='true'></i></button></td>";
            html_code += "</tr>";
            $('#crud_table').append(html_code);
        });

        $(document).on('click', '.remove', function() {
            var delete_row = $(this).data("row");
            $('#' + delete_row).remove();
        });


        $.ajaxSetup({
            cache: false,
            contentType: false,
            processData: false,

        });

        $('#save').submit(function(e) {


            e.preventDefault(); // ปิดการใช้งาน submit ปกติ เพื่อใช้งานผ่าน ajax
            var product = [];
            var amount = [];
            var unit = [];
            var price = [];



            var employee_code_request = $("#employee_code_request").val();
            var name_request = $("#name_request").val();
            var department = $("#department").val();
            var tel = $("#tel").val();
            var email = $("#email").val();
            var datepicker = $("#datepicker").val();
            var comment = $("#comment").val();
            var num_req = $("#num_req").val();
            var denumire = $("#denumire").val();
            var approver_user_code = $("#approver_user_code").val();
            var approver_user_first_level = $("#approver_user_first_level").val();

            var formData = new FormData($(this)[0]);

            $('.product').each(function() {
                product.push($(this).text());
            });
            $('.amount').each(function() {
                amount.push($(this).text());
            });
            $('.unit').each(function() {
                unit.push($(this).text());
            });

            event.preventDefault(); //prevent default submitting
            var formData = new FormData($(this)[0]);

            for (var i = 0; i < product.length; i++) {
                formData.append('product[]', product[i]);
            }
            for (var j = 0; j < amount.length; j++) {
                formData.append('amount[]', amount[j]);
            }
            for (var k = 0; k < unit.length; k++) {
                formData.append('unit[]', unit[k]);
            }
            formData.append('files[]', $('input[type=file]')[0].files[0]);



            $.post("db_save_quatation.php", formData, function(data) {
                // console.log(data); // ทดสอบแสดงค่า  ดูผ่านหน้า console
                /*              การใช้งาน console log เพื่อ debug javascript ใน chrome firefox และ ie */
                // alert(data);

                window.location.href = window.location.href; //This is a possibility

            });



        });



    });
    </script>


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

    <!-- Include Bootstrap Datepicker -->
    <link rel="stylesheet" href="css/font-awesome.css" />
    <link rel="stylesheet" href="css/bootstrap-datepicker.min.css" />
    <script src="js/bootstrap-datepicker.min.js"></script>


</head>

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
                                    <h2 class="m-b-20" style="color: white;">Add Quotation</h2>
                                    <div class="top-campaign">


                                        <div class="card-title">
                                            <div class="row">
                                                <div class="col-md-3 col-sm-4"></div>
                                                <div class="col-md-6 col-sm-4">
                                                    <h3 class="text-center title-10"><i class="fa fa-file-text"
                                                            aria-hidden="true"></i> Request for Quotation</h3>
                                                </div>

                                                <?php
                                          include("connect.php");
                                          $month = date('m');
                                          $year = date('Y');
                                          $x = "001";
                                          $fisrt_numRequire = 'REQ';
                                          $x_str = substr("$x", 0);


                                          if ($conn) {
                                             $query = "SELECT TOP 1 * FROM quatation ORDER BY quatation_ID DESC";
                                             $returnedValue = sqlsrv_query($conn, $query);
                                             $row = sqlsrv_fetch_array($returnedValue, SQLSRV_FETCH_ASSOC);

                                             if ($row === false) {
                                                // echo "Error while fetching array.\n";
                                                die(print_r(sqlsrv_errors(), true));
                                             } else if ($row === null) {
                                                // echo "No results were found.\n";
                                                $numRequire = $fisrt_numRequire  . $year . "-" . $month . "-" . $x_str;
                                             } else {
                                                do {
                                                   // echo "Inside loop!";
                                                   $mymonth = date_format($row["date_time_stamp"], "m");
                                                   // echo ">>>>$mymonth.\n";
                                                   if ($mymonth == $month) {
                                                      // echo "mymonth == month.\n";
                                                      // echo "====";
                                                      // $y = substr($row["num_req"], -3, 3);
                                                      // $z =  (int)$y + 1;
                                                      // $x_str = substr("000" . $z, -3, 3);

                                                      $y = substr($row["num_req"], -3, 3);
                                                      $z =  (int)$y + 1;
                                                      $x_str = substr("000" . $z, -3, 3);

                                                      $numRequire = $fisrt_numRequire . $year . "-" . $month . "-" . $x_str;
                                                   } else {
                                                      // echo "else.\n";
                                                      // echo "else";
                                                      $y = "000";
                                                      $z =  (int)$y + 1;
                                                      $x_str = substr("000" . $z, -3, 3);
                                                      $numRequire = $fisrt_numRequire . $year . "-" . $month . "-" . $x_str;
                                                   }
                                                } while ($row = sqlsrv_fetch_array($returnedValue, SQLSRV_FETCH_ASSOC));
                                             }
                                          } else {
                                             die(print_r(sqlsrv_errors(), true));
                                             echo "aaaa";
                                          }




                                    ?>

                                                <div class="col-md-3 col-sm-4">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo  "$numRequire" ?>" disabled>

                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <form action="" name="save" id="save" method="post"
                                            ENCTYPE="multipart/form-data">

                                            <div class="card-body">
                                                <input id="num_req" name="num_req" type="text"
                                                    value="<?php echo  $numRequire ?>" hidden>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <strong class="card-title"><label
                                                                    for="employee_code_request"
                                                                    class="control-label mb-1"><i
                                                                        class="fa fa-address-card-o"
                                                                        aria-hidden="true"></i> Employee
                                                                    Code</label></strong>
                                                            <input id="employee_code_request"
                                                                name="employee_code_request"
                                                                value="<?php echo $resultT["EmployeeCode"] ?>"
                                                                type="text" class="form-control" aria-required="true"
                                                                aria-invalid="false" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <strong class="card-title"><label for="name_request"
                                                                    class="control-label mb-1"><i
                                                                        class="fa fa-user-circle-o"
                                                                        aria-hidden="true"></i> Requester's
                                                                    name</label></strong>
                                                            <input id="name_request" name="name_request"
                                                                value="<?php echo $resultT["ThFullName"] ?>" type="text"
                                                                class="form-control" aria-required="true"
                                                                aria-invalid="false" readonly>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group has-success">
                                                            <strong class="card-title"><label for="department"
                                                                    class="control-label mb-1"><i class="fa fa-home"
                                                                        aria-hidden="true"></i>
                                                                    Department</label></strong>
                                                            <input id="department" name="department"
                                                                value="<?php echo $resultT["DepartmentName"] ?>"
                                                                type="text" class="form-control department valid"
                                                                aria-required="true" aria-invalid="false" readonly>

                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <strong class="card-title"><label for="tel"
                                                                    class="control-label mb-1"><i class="fa fa-phone"
                                                                        aria-hidden="true"></i> Tel</label></strong>
                                                            <input id="tel" name="tel" type="tel"
                                                                value="<?php echo $resultT["ContactNo"] ?>"
                                                                class="form-control tel" data-val="true"
                                                                data-val-required="Please enter tel number"
                                                                data-val-tel="Please enter a valid tel number"
                                                                autocomplete="tel" readonly>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <strong class="card-title"><label for="email"
                                                                    class="control-label mb-1"><i class="fa fa-envelope"
                                                                        aria-hidden="true"></i> Email</label></strong>
                                                            <input id="email" name="email" type="email"
                                                                value="<?php echo $resultT["Email"] ?>"
                                                                class="form-control email" value="" data-val="true"
                                                                data-val-required="Please enter your email"
                                                                data-email="Please enter email" autocomplete="email"
                                                                readonly>
                                                            <span class="help-block" data-valmsg-for="email"
                                                                data-valmsg-replace="true"></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-6">
                                                        <strong><label for="rfq_type" class="form-select mb-1"><i
                                                                    class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                RFQ. Type</label></strong>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text" for="rfq_type">RFQ.
                                                                    Type</label>
                                                            </div>
                                                            <select class="custom-select" id="rfq_type" name="rfq_type">
                                                                <option value="">Please select an RFQ. Type . . .
                                                                </option>
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

                                                                <option value="<?php echo $resultType["Id"]; ?>">
                                                                    <?php echo $resultType["RFQ_Type"]; ?> </option>
                                                                <?php
                                                                     } while ($resultType = sqlsrv_fetch_array($queryType, SQLSRV_FETCH_ASSOC));
                                                                  }
                                                                  ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <!-- <div class="row">
                                       <div class="col-6">


                                  
                                          <div class="form-group">
                                             <strong class="card-title"><label for="datepicker"><i class="fa fa-calendar" aria-hidden="true"></i> Expected date</label></strong>

                                             <div class="input-group">
                                                <input class="datepicker form-control" data-provide="datepicker" id="datepicker" name="datepicker" autocomplete="off">

                                                <div class="input-group-btn">
                                                   <span class="btn btn-secondary"><i class="fa fa-calendar"></i></span>

                                                </div>
                                             </div>

                                          </div>
                                       </div>

                                    </div> -->


                                                <div class="container-fluid" style="padding: 0px;">


                                                    <div class="card">
                                                        <div class="card-header"><strong class="card-title"><i
                                                                    class="fa fa-file-text" aria-hidden="true"
                                                                    style="margin-right: 10px;"></i>Add product
                                                                (Multiple)</i></div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table id="crud_table">
                                                                    <thead>
                                                                        <tr>

                                                                            <th class="col-md-5 col-sm">Product name  (ห้ามใส่เครื่องหมาย " ใน Product name)
                                                                            </th>
                                                                            <th class="col-md-2 col-sm">Quantity</th>
                                                                            <th class="col-md-2 col-sm">Unit</th>
                                                                            <!-- <th class="col-md-2 col-sm" style="text-align: left;">ราคา</th> -->
                                                                            <th width="col-md-1 col-sm">Delete item</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <td contenteditable="true" class="product"
                                                                            required></td>
                                                                        <td contenteditable="true" class="amount"
                                                                            required></td>
                                                                        <td contenteditable="true" class="unit"
                                                                            required></td>
                                                                        <!-- <td class="price" disabled></td> -->
                                                                        <td></td>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- <input type="button" class="add-row" value="Add Row"> -->  <!-- Disable add item ny 02019jp 19/05/2023       -->
                                                            <!-- <input class="au-btn au-btn-icon au-btn--green au-btn--small" class="add-row" value="add item">            -->
                                                            
                                                            <!-- <button type="button" name="add" id="add"
                                                                class=" au-btn au-btn-icon au-btn--green au-btn--small">Add
                                                                item <i class="fa fa-plus"></i></button>                -->

                                                        </div>
                                                    </div>


                                                </div>

                                                <!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx      UPLOAD PDF    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->
                                                <!-- <div class="row" style="margin-top:10px;">
                                             <div class="col-12">
                                                <label for="file_pdf" class="form-label">Upload File PDF (รวมเป็นไฟล์เดียว) : </label>
                                                <input type='file' name='file_pdf' id='file_pdf' />
                                             </div>
                                          </div> -->


                                                <div class="row">
                                                    <div class="col-12" style=" margin-bottom: 10px;">
                                                        <!-- <label for="input-id"><i class="fa fa-plus" aria-hidden="true"></i> Upload files (Multiple) <label style="color: red;">* .png .jpg .jpeg .pdf .zip .rar .7z (<= 30MB)</label></label> -->
                                                        <label for="input-id"><i class="fa fa-plus"
                                                                aria-hidden="true"></i> Upload files (Multiple) <label
                                                                style="color: red;">(<= 30MB per file
                                                                    ชื่อไฟล์ต้องเป็นภาษาอังกฤษและตัวเลข 0-9
                                                                    เท่านั้น)</label></label>
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
                                                        //    document.getElementById("managerApprove").style.marginTop = "0px";
                                                        //    return false;
                                                        // } else {
                                                        if (fileSize > 30) {
                                                            alert('File size exceeds 30 MiB');
                                                            files = [];
                                                            $(this).next('.custom-file-label').html(
                                                                'Choose file');
                                                            document.getElementById("managerApprove").style
                                                                .marginTop = "0px";
                                                            return false;
                                                            // $(file).val(''); //for clearing with Jquery
                                                        } else {}
                                                        // }

                                                        var english =
                                                            /^([a-zA-Z0-9-_()\s]+)\.(?!\.)([a-zA-Z0-9-_()\s]{1,5})(?<!\.)$/;

                                                        if (english.test($(this)[0].files[i].name)) {

                                                            console.log($(this)[0].files[i].name);
                                                            $("#flash-msg-danger").hide();
                                                        } else {


                                                            alert('File name error : ' + $(this)[0].files[i]
                                                                .name +
                                                                ' ชื่อไฟล์ต้องเป็นภาษาอังกฤษและตัวเลข 0-9 เท่านั้น'
                                                            );
                                                            files = [];
                                                            $(this).next('.custom-file-label').html(
                                                                'Choose file');
                                                            document.getElementById("managerApprove").style
                                                                .marginTop = "0px";
                                                            return false;

                                                        }
                                                        // if (!$(this)[i].files[i].name.match(/^[-A-Za-z0-9._]+$/)) {
                                                        //    // contains illegal characters
                                                        //    alert($(this)[i].files[i].name);
                                                        // } else {
                                                        //    alert('ชื่อไฟล์ต้องเป็น Eng หรือ ตัวเลข 0-9 เท่านั้น');
                                                        //    files = [];
                                                        //    $(this).next('.custom-file-label').html('Choose file');
                                                        //    document.getElementById("tableFile1").style.marginTop = "0px";
                                                        //    return false;
                                                        // }

                                                        // if ($regex.exec($(this)[i].files[i].name)) {
                                                        //    alert($(this)[i].files[i].name);

                                                    }

                                                    $(this).next('.custom-file-label').html(files.join(
                                                        '<br> '));
                                                    var clientHeight = document.getElementById(
                                                        "custom-file-label").clientHeight;

                                                    var h = clientHeight + 'px';
                                                    document.getElementById("managerApprove").style.marginTop =
                                                        h;

                                                });
                                                </script>
                                                <!-- //* javascript ไว้สำหรับเด้งแจ้งเตือนให้อัพโหลดไฟล์เฉพาะ png jpg pdf เท่านั้น -->
                                                <div class="row" id="managerApprove">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <strong class="card-title">
                                                                <label for="approver_user_name"
                                                                    class="control-label mb-1"><i
                                                                        class="fa fa-user-circle-o"
                                                                        aria-hidden="true"></i> Approver
                                                                </label>
                                                            </strong>
                                                            <?php
                                                                  include("connect.php");

                                                                  //$sqlT = "SELECT * FROM vw_Autherization where DepartmentCode = '$DepartmentCode' and status = 'ACTIVE' "; // 20220123 comment by 04404
                                                                  $sqlT = "SELECT Top 1 * FROM ApproveLevelVw Where Plant = '$EmployeePlantCode' And Dept = '$DepartmentCode' And Flag_Active = 1 Order By Lv Asc "; // 20220123 add by 04404
                                                                  $queryT = sqlsrv_query($conn, $sqlT);
                                                                  $resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC);
                                                                  if (!$resultT) {
                                                                     // echo "Error while fetching array.\n";
                                                                     // header("Location: Dashboard.php");
                                                                     echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                                     echo "<script>window.location.href='Dashboard.php';</script>";
                                                                     exit;
                                                                  } else if ($resultT === null) {
                                                                     // header("Location: Dashboard.php");
                                                                     echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                                     echo "<script>window.location.href='Dashboard.php';</script>";
                                                                     exit;
                                                                  } else {
                                                                     do {
                                                                        $ApproverUserCode = $resultT["Emp_Num"];
                                                                        $ApproverUserName = $resultT["ThFullName"];
                                                                        $ApproverUserLv = $resultT["Lv"];
                                                                     } while ($resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC));
                                                                  }
                                                            ?>
                                                            <input id="approver_user_name" name="approver_user_name"
                                                                value="<?php echo $ApproverUserName ?>" type="text"
                                                                class="form-control" aria-required="true"
                                                                aria-invalid="false" readonly>

                                                        </div>

                                                    </div>
                                                    <div class="col-12">
                                                        <input type='text' class="form-control" id='approver_user_code' name='approver_user_code'
                                                            value='<?php echo ''.$ApproverUserCode ?>' style='display: none;'>
                                                        <input type='text' class="form-control" id='approver_user_first_level'
                                                            name='approver_user_first_level'
                                                            value='<?php echo ''.$ApproverUserLv ?>' style='display: none;'>
                                                    </div>

                                                    <!-- <div class="col-12">
                                                        <label for="approver_user_code" class="form-select"><i
                                                                class="fa fa-user-circle" aria-hidden="true"></i>
                                                            Manager User</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text"
                                                                    for="approver_user_code">Approver</label>
                                                            </div>
                                                            <select disabled class="custom-select"
                                                                id="approver_user_code" name="approver_user_code">
                                                                <option value="">Please select an approver . . .
                                                                </option>
                                                                <?php
                                                                //   include("connect.php");

                                                                //   $sqlT = "SELECT * FROM vw_Autherization where DepartmentCode = '$DepartmentCode' and status = 'ACTIVE' ";
                                                                //   $queryT = sqlsrv_query($conn, $sqlT);
                                                                //   $resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC);
                                                                //   if (!$resultT) {
                                                                //      // echo "Error while fetching array.\n";
                                                                //      // header("Location: Dashboard.php");
                                                                //      echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                                //      echo "<script>window.location.href='Dashboard.php';</script>";
                                                                //      exit;
                                                                //   } else if ($resultT === null) {
                                                                //      // header("Location: Dashboard.php");
                                                                //      echo "<script>alert('ไม่มีผู้อนุมัติในระบบ กรุณาติดต่อ Admin');</script>";
                                                                //      echo "<script>window.location.href='Dashboard.php';</script>";
                                                                //      exit;
                                                                //   } else {
                                                                //      do { ?>

                                                                <option selected
                                                                    value="<?php //echo $resultT["EmployeeCode"]; ?>">
                                                                    <?php //echo $resultT["ThFullName"]; ?> </option>
                                                                <?php
                                                                     //} while ($resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC));
                                                                  //}
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div> -->

                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <label for="comment" class="form-label"><i
                                                                class="fa fa-commenting-o" aria-hidden="true"></i>
                                                            Comment</label>
                                                        <textarea class="form-control" id="comment" name="comment"
                                                            rows="3"
                                                            placeholder="Please , enter a comment . . ."></textarea>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row">
                                                <div class="col-12">

                                                    <button class="btn btn-block btn-primary  id=" submit" type="submit"
                                                        onclick=" return confirm('ยืนยันการบันทึก')"><i
                                                            class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                                                </div>
                                            </div>

                                    </div>

                                    </form>



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


    <?php
                                       } while ($resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC));
                                    }
?>




    <div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
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
                    <a type="submit" id="submit" class="btn btn-success success">Submit555</a>
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
        // format: 'dd-mm-yyyy',
        format: 'dd-mm-yyyy',

        autoclose: true

    }).datepicker("setDate", 'now');
    </script>




</body>

</html>





<!-- end document-->
<?php sqlsrv_close($conn); ?>