<!DOCTYPE html>
<html lang="en">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

<head>
  <!-- Required meta tags-->
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

<body class="animsition" style="overflow:hidden">
  <div class="page-wrapper">
    <div class="page-content--bge5" style="  
  background-image: url('images/closeup-people-using-digital-tablet.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: 100% 120%;
  overflow-y: hidden;
 
  

  ">

      <div class="container" style="  height: 100%; 
  ">
        <div class="login-wrap" style=" max-width: 600px; ">
          <div class="login-content" style="box-shadow: 5px 10px 18px #888888;">
            <div class="login-logo">
              <a href="#">

                <h2>Request for quotation</h2>
                <!--<h1>DEMO</h1>-->
              </a>

              <h4 style="margin-top: 10px;"><span id="typed"></span></h4>
            </div>
            <div class="login-form">
              <form action="loginclass.php" method="post">
                <div class="form-group">






                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-user"></i>
                    </div>
                    <!-- <input type="text" id="username" name="username" placeholder="Username" class="form-control"> -->
                    <input class="form-control" type="Username" name="Username" placeholder="Username" required>
                  </div>



                  <!-- <label>Username</label>
                  <input class="au-input au-input--full" type="Username" name="Username" placeholder="Username"> -->
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-key" aria-hidden="true"></i>

                    </div>
                    <!-- <input type="password" id="password" name="password" placeholder="Password" class="form-control"> -->
                    <input class="form-control" type="Password" name="Password" placeholder="Password" required>

                    <!-- <label>Password</label>
                  <input class="au-input au-input--full" type="Password" name="Password" placeholder="Password"> -->
                  </div>
                </div>


                <button class="au-btn au-btn--block au-btn--blue m-b-20  " style="letter-spacing: 2px;" type="submit">Login</button>
                <div>
                  <!--   <p style="font-family: Arial, Helvetica, sans-serif; font-size: small; color:darkslategray">* การ Login เข้าใช้งานโปรแกรมให้ใช้ Username และ Password เดียวกับการเข้าใช้งานคอมพิวเตอร์</p>
                  <p style="font-family: Arial, Helvetica, sans-serif; font-size: small; color:darkslategray">** ใช้ browser Google Chrome (Update version ล่าสุด) ในการเข้าใช้งานโปรแกรม</p> -->

                  <p style="font-size: smaller; margin-bottom: 0px;">* Login to the program using the same username and password as accessing the computer.</p>

                  <p style="font-size: smaller;  padding-top: 10px;margin-bottom: 0px;">** Use the <b>Google Chrome</b> browser (latest update version) to access the program.</p>
                  <p style="text-align: right;font-family: Arial, Helvetica, sans-serif; font-size: small; color:darkslategray">Version 1.70 (1492022)


                    <!-- 
                    <span data-toggle="modal" data-target="#exampleModal">
                      <button type="button" data-placement="right" title="คลิกเพื่อดู Update History" data-toggle="tooltip" class="btn btn-info btn-sm"><i class="fa fa-info-circle" aria-hidden="true"></i></button>
                    </span> -->


                    <span data-toggle="modal" data-target="#exampleModal">
                      <button onclick="disappearBadge()" type="button" class="btn btn-info btn-sm position-relative" data-placement="right" title="คลิกเพื่อดู Update History" data-toggle="tooltip">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle" id="badgeAlert">
                          <!-- <span class="visually-hidden">New alerts</span> -->
                        </span>
                      </button>

                      <script>
                        function disappearBadge() {
                          document.getElementById("badgeAlert").style.display = "none";
                        }
                      </script>
                    </span>


                  </p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">View Update History</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label class="col-form-label">14/9/2022 : Version 1.70 (1492022)</label> <span class="badge badge-danger">new</span>
              <p type="text" class="form-control">


                ✓ เพิ่มการ preview ไฟล์ตัวอย่าง นามสกุล 'gif', 'png', 'jpg', 'pdf', 'jpeg', 'jfif' <br>


              </p>
            </div>
            <div class="form-group">
              <label class="col-form-label">19/8/2022 : Version 1.60 (1982022)</label>
              <p type="text" class="form-control">


                ✓ แก้ไขขนาด Font ใน Email ให้ใหญ่ขึ้น <br>
                ✓ แก้ไขให้มีการแจ้งเตือนหาก User ที่เข้าใช้งานแล้วไม่มีผู้อนุมัติ <br>

              </p>
            </div>

            <div class="form-group">
              <label class="col-form-label">11/8/2022 : Version 1.50 (1182022)</label>
              <p type="text" class="form-control">


                ✓ แก้ไขขั้นตอนหลังที่ผจก.ของหน่วยงานผู้ร้องขอมายังผจก.จัดซื้อของให้มี การอนุมัติกับไม่อนุมัติ <br>
                ✓ แก้ไขการเลือกวันที่ของ approver pu <br>

              </p>
            </div>

            <div class="form-group">
              <label class="col-form-label">12/7/2022 : Version 1.40 (1272022)</label>
              <p type="text" class="form-control">


                ✓ แก้ไขกรณีเมื่อกด submit quotation พร้อมกันแล้วทำให้เลข quotation และข้อมูลซ้ำซ้อนกัน <br>

              </p>
            </div>
            <div class="form-group">
              <label class="col-form-label">30/6/2022 : Version 1.30 (3062022)</label>
              <p type="text" class="form-control">


                ✓ แก้ไขให้ผจก.จัดซื้อเป็นผู้ระบุวันกำหนดเสร็จเอง<br>
                ✓ แก้ไขให้โชว์ชื่อผู้รับผิดชอบหลังผจก.จัดซื้อจ่ายงาน<br>
                ✓ แก้ไขหลังจาก ผจก.จัดซื้ออนุมัติจ่ายงานให้แต่ละคน ตอนเจ้าหน้าที่จัดซื้อเข้าระบบให้โชว์เฉพาะงานแต่ละคน
                <br>

              </p>
            </div>


            <div class="form-group">
              <label class="col-form-label">6/5/2022 : Version 1.20 (652022)</label>
              <p type="text" class="form-control">


                ✓ เพิ่มหน้าระบบ Edit Admin ใช้สำหรับจัดการ เพิ่ม / ลบ Admin ได้ <br>

              </p>
            </div>
            <div class="form-group">
              <label class="col-form-label">4/5/2022 : Version 1.10 (452022)</label>
              <p type="text" class="form-control">

                ✓ เพิ่ม แนบไฟล์ user (แบบ multiple file by JavaScript Ajax) <br>
                ✓ เป็นสกุลไฟล์ file .jpeg .png .pdf etc.<br>
                ✓ เพิ่มการย้อน process ของ purchase และ user หลังจากที่ไม่ผ่านการ approve ให้สามารถแก้ไขข้อมูล / เพิ่มไฟล์ แก้ไขไฟล์ ได้<br>
                ✓ ลำดับขั้นตอนการอนุมัติดังนี้ Approve user > manager user > purchase manager > admin > purchase manager<br>
                ✓ เพิ่มระบบ Comment เพิ่ม Log เวลาในการ Comment แต่ละครั้งหลัง @<br>
                ✓ UI เพิ่ม icon การแจ้งสถานะ ว่าถึงขั้นตอนใดแล้ว ในหน้า Dashboard<br>
                ✓ เพิ่มปุ่ม Delete ใบ Quotation สำหรับ Admin (ต้องแจ้งให้กำหนด Admin)<br>
                ✓ เพิ่มข้อจำกัดการอัพโหลดไฟล์ โดยการตั้งชื่อไฟล์ เฉพาะ Character A-Z a-z 0-9 (Regular Expression JavaScript)<br>



              </p>
            </div>
            <div class="form-group">
              <label class="col-form-label">6/5/2022 : Version 1.2.0 (652022)
              </label>
              <p class="form-control">
                ✓ เพิ่มหน้าระบบ Edit Admin ใช้สำหรับจัดการ เพิ่ม / ลบ Admin ได้


              </p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

        </div>
      </div>
    </div>
  </div>

  <!-- Jquery JS-->
  <script src="vendor/jquery-3.2.1.min.js"></script>
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
  <script src="vendor/select2/select2.min.js"></script>

  <script src="vendor/typedjs/typed.min.js"></script>

  <script>
    $(function() {
      $("#typed").typed({
        strings: [
          "Login",
          "All Request quotation",
          "All Works",
          "All Purchasing",
          "by IT, Thai Summit Engineering Co., Ltd.",

        ],
        typeSpeed: 20,
        // time before typing starts
        startDelay: 1200,
        // backspacing speed
        backSpeed: 20,
        // time before backspacing

        // loop
        loop: true,
        shuffle: true
      })
    })
  </script>





  <!-- Main JS-->
  <script src="js/main.js"></script>

</body>


</html>
<!-- end document-->