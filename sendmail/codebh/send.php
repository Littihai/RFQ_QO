<?PHP
ob_start();

 require("../../config.php");  
 require("../PHPMailer_v5.1/class.phpmailer.php");  // ประกาศใช้ class phpmailer กรุณาตรวจสอบ ว่าประกาศถูก path
 
	if($txtfrommail=="" OR $txttopic=="" OR $txtdetail=="")
	{
		header("Location: ../index.php?sch=search&schdocname=$schdocname&schdocnum=$schdocnum&schrevision=$schrevision&schauth=$schauth&backsearch=$backsearch&errormail=เกิดข้อผิดพลาด : กรุณากรอกข้อมูลในช่อง E-mail ผู้ส่ง,หัวเรื่อง,วัตถุประสงค์และรายละเอียด ทั้งหมดให้ครบ");
	}
	elseif (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $txtfrommail))
	{
		header("Location: ../index.php?sch=search&schdocname=$schdocname&schdocnum=$schdocnum&schrevision=$schrevision&schauth=$schauth&backsearch=$backsearch&errormail=เกิดข้อผิดพลาด : กรุณากรอกข้อมูลในช่อง E-mail ให้ถูกต้อง");
	}
	else
	{
	//ข้อความที่จะส่ง
$txtdetailsend = "$txtdetail"."วัตถุประสงค์\n$txtobjective\n\nFrom $desc_user\n\n";

 function smtpmail( $email , $email1 ,  $email2 , $email3 ,$email4 , $subject , $body , $from_mail , $from_name , $email_doc , $passmail_doc , $hostsmtp)
 {
     $mail = new PHPMailer();
     $mail->IsSMTP();          
      $mail->CharSet = "utf-8";  // ในส่วนนี้ ถ้าระบบเราใช้ tis-620 หรือ windows-874 สามารถแก้ไขเปลี่ยนได้                         
    $mail->SMTPAuth = true;     //  เลือกการใช้งานส่งเมล์ แบบ SMTP
	//$mail->SMTPSecure = "ssl"; 
	$mail->Host     = $hostsmtp; //  mail server ของเรา
     $mail->Username = $email_doc;   //  account e-mail ของเราที่ต้องการจะส่ง
    $mail->Password = $passmail_doc;  //  รหัสผ่าน e-mail ของเราที่ต้องการจะส่ง
    $mail->From     = "$from_mail";  //  account e-mail ของเราที่ใช้ในการส่งอีเมล
    $mail->FromName = "$from_name"; //  ชื่อผู้ส่งที่แสดง เมื่อผู้รับได้รับเมล์ของเรา
    $mail->AddAddress($email);            // Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
	$mail->AddAddress($email1);            // Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
    $mail->AddAddress($email2);	
	$mail->AddAddress($email3);  	// Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
    $mail->AddAddress($email4); 
	$mail->IsHTML(false);                  // ถ้า E-mail นี้ มีข้อความในการส่งเป็น tag html ต้องแก้ไข เป็น true
     $mail->Subject     =  $subject;        // หัวข้อที่จะส่ง(ไม่ต้องแก้ไข)
     $mail->Body     = $body;                   // ข้อความ ที่จะส่ง(ไม่ต้องแก้ไข)
      $result = $mail->send();        
     return $result;
 }
 smtpmail($confic_emaildoc,$confic_emaildoc1,$confic_emaildoc2,$confic_emaildoc3,$confic_emaildoc4,$txttopic,$txtdetailsend,$txtfrommail,$desc_user,$confic_emailsender,$confic_passwordmaildoc,$confic_hostsmtp);
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LIBRARY ONLINE</title>
<style type="text/css">
<!--
.txtwi14bold {
	font-family: Tahoma;
	font-size: 14px;
	color: #0000FF;
	font-weight: bold;
}
.bgwi {
	background-color: #99CCFF;
	border: thin double #0099FF;
	font-family: Tahoma;
	font-size: 12px;
	height: 25px;
	width: 150px;
}
.txtgray14 {
	font-family: Tahoma;
	font-size: 12px;
	color: #CCCCCC;
}
.bgwi {
	background-color: #99CCFF;
	border: thin double #0099FF;
	font-family: Tahoma;
	font-size: 12px;
	height: 25px;
	width: 150px;
}
.borderblue {
	border: thin ridge #999999;
	font-family: Tahoma;
	font-size: 12px;
	color: #000000;
}
a:link {
	text-decoration: none;
	color: #0000FF;
}
.txtdetail {
	font-family: Tahoma;
	color: #000000;
	font-size: 12px;
}
a:visited {
	text-decoration: none;
	color: #0000FF;
}
a:hover {
	text-decoration: none;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #0000FF;
}
body {
	margin-top: 0px;
	margin-left: 0px;
	margin-right: 0px;
}
.txttopic {
	font-family: Tahoma;
	font-size: 12px;
	color: #000000;
}
.txthead {
	font-family: Tahoma;
	font-size: 16px;
	color: #0000CC;
	text-decoration: underline;
}
.txterror {
	font-family: Tahoma;
	font-size: 12px;
	color: #FF0000;
}
-->
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#99CCFF" >
      <tr>
        <td height="35" align="left" valign="middle" bordercolor="#0099FF" bgcolor="#FFCC66" class="txtwi14bold">&nbsp;<a href="../../index.php">HOME</a>
		<? if($user == 'sa'){?> | <a href="../../user/index.php">User</a><? } ?>
		<? if($insert == '1'){?> | <a href="../../upload/index.php">Upload Document</a><? } ?>
|&nbsp;		<span class="txtgray14"><a href="../../codebh/logout.php">Logout</a></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="right" bgcolor="#FFFFCC"><span class="txtgray14"><? echo"$desc_user";?>&nbsp;&nbsp; </span></td>
  </tr>
  <tr>
    <td colspan="3" align="right" bgcolor="#FFFFCC" class="txtgray14">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="right" bgcolor="#FFFFCC" class="txtgray14">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="right" class="txtgray14">&nbsp;</td>
  </tr>
  <tr>
    <td width="13%" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center"><img src="../../image/Logo TSE.jpg" width="160" height="240" /></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table></td>
    <td width="82%" align="center" valign="top">
        <table width="100%" border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td width="9%" align="right" class="txtdetail">&nbsp;</td>
            <td width="91%" align="left" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left"><span class="txthead">Contract Us </span></td>
              </tr>
              <tr>
                <td align="left"><span class="txttopic">เจ้าหน้าที่ Document  :</span> <span class="txtdetail"><? echo"$confic_namedoc";?></span></td>
              </tr>
              <tr>
                <td align="left"><span class="txttopic">โทรศัพท์ :</span> <span class="txtdetail"><? echo"$confic_phonedoc";?></span></td>
              </tr>
              <tr>
                <td align="left"><span class="txttopic">E-mail :</span> <span class="txtdetail"><? echo"$confic_emaildoc,$confic_emaildoc1,$confic_emaildoc2,$confic_emaildoc3,$confic_emaildoc4";?></span></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="right" class="txtdetail">&nbsp;</td>
            <td width="91%" align="left" ><span class="txthead">Send Mail </span>
              <img src="../../image/Email-Marketing.jpg" width="30" height="30" /></td>
          </tr>
          <tr>
            <td align="right" class="txtdetail">&nbsp;</td>
            <td align="left" class="txtdetail" style="font-size:16px" >ส่งข้อมูลเรียบร้อยแล้ว <? echo"<a href='http://srvad4:81/library-onlines/login/index.php' style='color:#F00'>Click เพื่อย้อนกลับ</a>";?></td>
          </tr>
          <tr>
            <td align="right" class="txtdetail">&nbsp;</td>
            <td align="left"><img src="../../image/Email-Marketing.jpg" width="172" height="182" /></td>
          </tr>
          <tr>
            <td align="right" class="txtdetail">&nbsp;</td>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td align="right" class="txtdetail">&nbsp;</td>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="left">&nbsp;</td>
          </tr>
        </table>
		</td>
    <td width="5%" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
</table>
</body>
</html>
