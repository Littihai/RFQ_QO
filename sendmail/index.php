<?
ob_start();
session_start();				
require '../codebh/databaselib.php';
require '../config.php';

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
	background-color: #FFCC66;
	border: thin double #000000;
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
	color: #000000;
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
        <td height="35" align="left" valign="middle" bordercolor="#0099FF" bgcolor="#FFCC66" class="txtwi14bold">&nbsp;<? if($backsearch<>""){echo"<a href='../view.php?txtsearch=$backsearch&search=Search'>BACK</a> | ";}?><a href="../index.php">HOME</a>
		<? if($user == 'sa'){?> | <a href="../user/index.php">User</a><? } ?>
		<? if($insert == '1'){?> | <a href="../upload/index.php">Upload Document</a><? } ?>
|&nbsp;		<span class="txtgray14"><a href="../codebh/logout.php">Logout</a></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="right" bgcolor="#FFFFCC"><span class="txtgray14"><? echo"$desc_user";?>&nbsp;&nbsp; </span></td>
  </tr>
  <tr>
    <td colspan="3" align="right" bgcolor="#FFFFCC" class="txttopic">Languages : <? echo" <a href='codebh/changelanguage.php?changelanguage=EN&sch=search&schdocname=$schdocname&schdocnum=$schdocnum&schrevision=$schrevision&schauth=$schauth&backsearch=$backsearch' style='color:#000000'>EN</a> | <a href='codebh/changelanguage.php?changelanguage=TH&sch=search&schdocname=$schdocname&schdocnum=$schdocnum&schrevision=$schrevision&schauth=$schauth&backsearch=$backsearch' style='color:#000000'>TH</a>"; ?>&nbsp;</td>
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
        <td align="center"><img src="../image/Logo TSE.jpg" width="160" height="240" /></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table></td>
    <td width="82%" align="center" valign="top"><form id="form1" name="form1" method="get" action="codebh/send.php">
        <table width="100%" border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td width="9%" align="right" class="txtdetail">&nbsp;</td>
            <td width="91%" align="left" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left"><span class="txthead">Contract Librarian </span></td>
              </tr>
              <tr>
                <td align="left"><span class="txttopic"><? if($language=="TH"){echo"เจ้าหน้าที่";}else{echo"Name";} ?>  :</span> <span class="txtdetail"><? echo"$confic_namedoc";?></span></td>
              </tr>
              <tr>
                <td align="left"><span class="txttopic"><? if($language=="TH"){echo"เบอร์ติดต่อ";}else{echo"Ext. No.";} ?> :</span> <span class="txtdetail"><? echo"$confic_phonedoc";?></span></td>
              </tr>
              <tr>
                <td align="left"><span class="txttopic">E-mail :</span> <span class="txtdetail"><? echo"$confic_emaildoc; $confic_emaildoc1; $confic_emaildoc2; $confic_emaildoc3; $confic_emaildoc4";?></span></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="right" class="txtdetail">&nbsp;</td>
            <td width="91%" align="left" ><span class="txthead">Send Mail <img src="../image/Email-Marketing.jpg" width="30" height="30" /></span>
              <input name="schdocname" type="hidden" id="schdocname" value="<? echo"$schdocname";?>" />
              <input name="schdocnum" type="hidden" id="schdocnum" value="<? echo"$schdocnum";?>"/>
              <input name="schrevision" type="hidden" id="schrevision" value="<? echo"$schrevision";?>"/>
              <input name="schauth" type="hidden" id="schauth" value="<? echo"$schauth";?>"/>
              <input name="backsearch" type="hidden" id="backsearch" value="<? echo"$backsearch";?>"/>
              <span class="txterror"><? echo"$errormail"; ?></span></td>
          </tr>

          <tr>
            <td align="right" class="txttopic">  <? if($language=="TH"){echo"E-mail ผู้ส่ง";}else{echo"E-mail Sender";} ?> : </td>
            <td align="left"><input name="txtfrommail" type="text" class="borderblue" id="txtfrommail" size="70" /></td>
          </tr>
          <tr>
            <td align="right" class="txttopic"><? if($language=="TH"){echo"หัวเรื่อง";}else{echo"Subject";} ?> : </td>
            <td align="left"><input name="txttopic" type="text" class="borderblue" id="txttopic" value="<? if($schrevision<>""){echo"ส่งคำร้องขอพิมพ์เอกสาร $schdocname";}else{echo"$schdocname";}?>" size="100"/></td>
          </tr>
          <tr>
            <td align="right" class="txttopic"><? if($language=="TH"){echo"วัตถุประสงค์";}else{echo"Objective";} ?> : </td>
            <td align="left"><input name="txtobjective" type="text" class="borderblue" id="txtobjective" size="80" /></td>
          </tr>
          <tr>
            <td align="right" valign="top" class="txttopic"><? if($language=="TH"){echo"รายละเอียด";}else{echo"Description";} ?> : </td>
            <td align="left">
            <textarea name="txtdetail" cols="100" rows="12" class="borderblue" id="txtdetail"><? if($schrevision<>""){
			 echo"To. Document Control\n\n";
			 echo"ส่งคำร้องขอพิมพ์เอกสาร\n\n"; 
			 echo"ชื่อเอกสาร :  $schdocname\n"; 
			 echo"เลขที่เอกสาร : $schdocnum\n"; 
			 echo"Revision : $schrevision\n";
			 echo"\n";}
			 else
			 {
			 echo"$schdocnum\n\n";
			 echo"$schauth\n";
			 }
			 ?> </textarea></td>
          </tr>
          <tr>
            <td align="right" class="txtdetail">&nbsp;</td>
            <td align="left"><input name="Submit" type="submit" class="bgwi" value="Send" /></td>
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
            <td align="right" class="txtdetail">&nbsp;</td>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="left">&nbsp;</td>
          </tr>
        </table>
      </form>    </td>
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
