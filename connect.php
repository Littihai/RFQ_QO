<?php

ini_set('sqlsrv.charset', 'UTF-8');

$serverName = 'TSECD';
// $serverName = 'TSA-WBSRV01.ts.tsa.co.th';
$userName = "sa";
$Password = "P@ssw0rd";
$dbName = "E-Form_Purchase_new_Qo";

$connectionInfo = array("Database" => $dbName, "UID" => $userName, "PWD" => $Password, "MultipleActiveResultSets" => true, "CharacterSet"  => 'UTF-8');



$conn = sqlsrv_connect($serverName, $connectionInfo);
$linkProgram = 'https://web.ts-engineering.com/RFQ_qo/';

$conn2 = new PDO("sqlsrv:server=$serverName ; Database = $dbName", $userName, $Password);
$conn2->exec('set names utf8');
$conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    // echo "<script type=\"text/javascript\">alert(\"Connected\");</script>";
}
