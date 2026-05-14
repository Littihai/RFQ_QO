<?
ob_start();
session_start();

unset($_SESSION["language"]);
$_SESSION["language"] = "$changelanguage";

header("location: ../index.php?sch=search&schdocname=$schdocname&schdocnum=$schdocnum&schrevision=$schrevision&schauth=$schauth&backsearch=$backsearch");

?>