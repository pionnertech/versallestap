<?php


$fac = $_GET['fac'];
$nombre = $_GET['name'];
$surname = $_GET['surmane'];
$importance = $_GET['imp'];
$msg = $_GET['msg'];
$dataF = $_GET['dataF'];
$dataS = $_GET['dataS'];
$iss_id = $_GET['iss_id'];


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

mysqli_query($datos, "UPDATE ISSUES SET ISS_CHARGE_USR = '" . $nombre . "' WHERE ISS_ID = " . $iss_id . ";");
mysqli_query($datos, "UPDATE ISSUES SET ISS_STATE = 2 WHERE ISS_ID = " . $iss_id   . ";");


$query_insert = "INSERT INTO SUBTASKS(STSK_ISS_ID, STSK_DESCRIP, STSK_CHARGE_USR , STSK_STATE, STSK_FINISH_DATE , STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE) ";
$query_insert .= "VALUES (" . $iss_id  . " , '" . $msg . "', '" . $nombre . "', 2, '" . $dataF . "','" . $dataS . "', '" . $nombre . "', " . $fac  . ")";

if(!mysqli_query($datos, $query_insert)){

echo "0";

} else {

echo "1";
}





?>