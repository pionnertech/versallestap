<?php


$fac = $_GET['fac'];
$usr_id = $_GET['usr_id'];
$surname = $_GET['surmane'];
$importance = $_GET['imp'];
$msg = $_GET['msg'];
$dataF = $_GET['dataF'];
$dataS = $_GET['dataS'];
$iss_id = $_GET['iss_id'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

mysqli_query($datos, "UPDATE ISSUES SET ISS_CHARGE_USR = '" . $usr_id  . "' WHERE ISS_ID = " . $iss_id . ";");
mysqli_query($datos, "UPDATE ISSUES SET ISS_STATE = 2 WHERE ISS_ID = " . $iss_id   . ";");
mysqli_query($datos, "UPDATE ISSUES SET ISS_FINISH_DATE = " . $dataF . " WHERE ISS_ID = " . $iss_id   . ";");


$query_insert = "INSERT INTO SUBTASKS(STSK_ISS_ID, STSK_DESCRIP, STSK_CHARGE_USR , STSK_STATE, STSK_FINISH_DATE , STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_LOCK) ";
$query_insert .= "VALUES (" . $iss_id  . " , '" . $msg . "', '" . $usr_id  . "', 2, '" . $dataF . "','" . $dataS . "', '" . $usr_id  . "', " . $fac  . ", 0 , 0)";

if(!mysqli_query($datos, $query_insert)){

echo "0";

} else {

echo "1";
}





?>