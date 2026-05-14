<?php
include("connect.php");
require('phpmailer/sendmail.php'); //20240808 add by 04404, new function send mail.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


try {
	if (isset($_GET["quatation_ID"])) {

		$quatation_ID = $_GET['quatation_ID'];
		$num_req = $_GET['num_req'];

		$sqlUser = "SELECT * FROM quatation WHERE quatation_ID = '$quatation_ID' ";
		$queryUser = sqlsrv_query($conn, $sqlUser);
		$resultUser = sqlsrv_fetch_array($queryUser, SQLSRV_FETCH_ASSOC);
		$name_request = $resultUser["name_request"];
		$employee_code_request = $resultUser["employee_code_request"];
		$email = $resultUser["email"];

		$approver_user_nameTH = $resultUser["approver_user_nameTH"];
		$approver_user_code = $resultUser["approver_user_code"];

		$date_time_stamp = $resultUser["date_time_stamp"];


		$sqlEmail = "SELECT * FROM vw_Employee WHERE EmployeeCode = '$approver_user_code' ";
		$queryEmail = sqlsrv_query($conn, $sqlEmail);
		$resultEmail = sqlsrv_fetch_array($queryEmail, SQLSRV_FETCH_ASSOC);
		$approver_user_email = $resultEmail["Email"];

		// ==============================================================
		$sql0 = "DELETE FROM TSE_CateLeadTime WHERE RFQNum = '$num_req'";
		// =====================Add delete cateleadtime==================
		$sql = "DELETE FROM quatation where quatation_ID ='$quatation_ID'";
		$sql1 = "DELETE FROM request_product where num_req ='$num_req'";


		$sqlT = "SELECT * FROM quatation_file where Num_req ='$num_req'";
		$queryT = sqlsrv_query($conn, $sqlT);
		$resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC);
		if ($resultT === false) {
			// ==============================================================
			sqlsrv_query($conn, "SET NAMES UTF8");
			$query0 = sqlsrv_query($conn, $sql0);
			// =====================Add delete cateleadtime 04726 ==================
			sqlsrv_query($conn, "SET NAMES UTF8");
			$query = sqlsrv_query($conn, $sql);
			sqlsrv_query($conn, "SET NAMES UTF8");
			$query1 = sqlsrv_query($conn, $sql1);
		} else if ($resultT === null) {
			sqlsrv_query($conn, "SET NAMES UTF8");
			$query = sqlsrv_query($conn, $sql);
			sqlsrv_query($conn, "SET NAMES UTF8");
			$query1 = sqlsrv_query($conn, $sql1);
			// ==============================================================
			sqlsrv_query($conn, "SET NAMES UTF8");
			$query0 = sqlsrv_query($conn, $sql0);
			// =====================Add delete cateleadtime 04726 ==================
		} else {
			do {

				$filename = dirname(__FILE__) . '/' .  'upload/' . $resultT["File_name"];

				if (is_file($filename)) {

					chmod($filename, 0777);

					if (unlink($filename)) {
						echo 'File deleted';
					} else {
						echo 'Cannot remove that file';
					}
				} else {
					echo 'File does not exist';
				}
			} while ($resultT = sqlsrv_fetch_array($queryT, SQLSRV_FETCH_ASSOC));
			$sql2 = "DELETE FROM quatation_file where Num_req ='$num_req'";
			sqlsrv_query($conn, "SET NAMES UTF8");
			$query2 = sqlsrv_query($conn, $sql2);
			 $folderPath = dirname(__FILE__) . '/' . 'upload/' . $num_req;

			// ฟังก์ชันลบทั้งโฟลเดอร์
			function deleteFolder($folder) {
				if (!file_exists($folder)) return;

				$files = scandir($folder);
				foreach ($files as $file) {
					if ($file != '.' && $file != '..') {
						$fullPath = $folder . '/' . $file;

						if (is_dir($fullPath)) {
							deleteFolder($fullPath);
						} else {
							unlink($fullPath);
						}
					}
				}

				rmdir($folder);
			}

			// ลบเฉพาะเมื่อมีโฟลเดอร์
			if (is_dir($folderPath)) {
				deleteFolder($folderPath);
			}

			// =====================Add delete cateleadtime 04726 ==================
			sqlsrv_query($conn, "SET NAMES UTF8");
			$query0 = sqlsrv_query($conn, $sql0);
			// ==============================================================
			sqlsrv_query($conn, "SET NAMES UTF8");
			$query = sqlsrv_query($conn, $sql);
			sqlsrv_query($conn, "SET NAMES UTF8");
			$query1 = sqlsrv_query($conn, $sql1);
		}



		$email_bcc = 'littichai_y@ts-engineering.com'; //add email by 04726 20260508

		if ($query) {



			date_default_timezone_set("Asia/Bangkok");
			$adate = date("Y-m-d");
			$ldate = date('Y-m-d', strtotime(' - 1 days'));

			$to = $email . ',' . $approver_user_email;
			$bcc = $email_bcc; // add email by 04726 20260508
			$subject = 'Alert from System > Request for quotation';
			$fromname = 'ระบบ Request for quotation';
			$message = '<html> 
										<body style="font-family:Tahoma">
										<FONT Size = "3"> 
										
											<H3><font color="blue">Request for quotation system (RFQ Online)</font></H3> 
											<H3><font color="blue">ข้อความจากระบบแจ้งเตือนอัตโนมัติ</font></H3> 

										   
											<b>Dear, ' . $name_request . ', ' . $approver_user_nameTH . '</b> <br> <br>
												
											&nbsp;&nbsp; ขณะนี้ฝ่ายจัดซื้อ ได้ดำเนินการ<font color="red">ยกเลิกและลบ</font> <b>เอกสารเลขที่ : ' . $num_req . '</b><br> 
		
											&nbsp;&nbsp; เนื่องจากพบว่าข้อมูลไม่ครบถ้วน หรือ ไม่ถูกต้อง กรุณาดำเนินการสร้างเอกสารใหม่ในระบบอีกครั้ง <br> 
												
											
											&nbsp;&nbsp; <table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
											<tbody>
											<tr>
											<td style="border: 2px solid #ccc; border-collapse: collapse;">
												<table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-family:Tahoma;font-size:16px">
														<thead>                                        
															<tr>
																<th>Status</th>
																<th width="15%">ผู้ร้องขอ</th>
																<th >อีเมล์</th>
																<th >แผนก</th>
																<th >เบอร์ติดต่อ</th>
																<th >วันที่ขอ</th>
																<th >วันที่อัพเดท</th>
															</tr>
														</thead>
														<tbody>
															<td >Unsuccess (ไม่สำเร็จ)</td>
															<td >' . $name_request . '</td>
															<td >' . $email . '</td>
															<td >' . $department . '</td>
															<td >' . $tel . '</td>
														 
															<td >' . date_format($date_time_stamp, "d/m/Y H:i:s") . '</td>
															<td >' .  date('d/m/Y, H:i:s')  . '</td>
														</tbody>
													</table>
													</td>
												</tr>
												</tbody>
												</table>    
												<br> 
		
												&nbsp;&nbsp; คลิกที่ลิงก์เพื่อเข้าใช้งานระบบ <br> 
												
												&nbsp;&nbsp; ' . $linkProgram . '<br><br>
												
												NOTICE: This mail is automatic from system. Please do not reply to this note.<br>
										
										</FONT>
										</body>
										</html>';
			$headers = 'From: NoReply.RFQ@ts-engineering.com' . "\r\n" .
				// 'Reply-To: no-reply@thaisummit.co.th' . "\r\n" .
				'Content-type: text/html; charset=utf-8' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

			// $Send = mail($to, $subject, $message, $headers);
			$Send = fncPhpMailer($to, $cc, $bcc, $subject, $message); // add cc, bcc by 04726 on 20260501

			if ($Send) {
				echo "Email Sending";
				session_start();
				$_SESSION['plan'] = "Delete Successfully";
				header("location: Dashboard.php");

				// session_start();
				// $_SESSION['plan'] = "Update Successfully and Email Sending! ";
				// header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
				// echo"<script type=\"text/javascript\">alert(\"ไม่สามารถ Update ค่าน้ำมันได้ เมลถูกส่งเรียบร้อยแล้ว!\");</script>";
			} else {
				echo "Email Can Not Send";
				session_start();
				$_SESSION['plan'] = "Delete Successfully but Email Can't Send!";
				header("location: Dashboard.php");

				// session_start();
				// $_SESSION['plan'] = "Update Successfully but Email Can't Send! ";
				// header("Location: Quatation_edit.php?quatation_ID=" . $quatation_ID);
				//echo"<script type=\"text/javascript\">alert(\"ไม่สามารถ Update ค่าน้ำมัน และส่งเมลไม่ได้!\");</script>";
			}














			// echo 'window.location = "ReportGroup.php"; ';

		} else {
			session_start();
			$_SESSION['plan'] = "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
			$_SESSION['plan_status'] = 'error';
			header("location: Dashboard.php");
		}
	}
	exit;
	sqlsrv_close($conn);
} catch (Error $e) {
	$trace = $e->getTrace();
	echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . ' called from ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'];
}
