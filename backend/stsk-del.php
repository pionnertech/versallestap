<?php

$muser = $_GET['muser'];
$user = $_GET['user'];
$fechaF = $_GET['fechaF'];
$iss_id = $_GET['iss_id'];
$stsk_id = $_GET['stsk'];
$subject = $_GET['subject'];
$descript = $_GET['descript'];
$startD = $_GET['startD'] . " 10:00:00";
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS) ";
$query .= "VALUES (" . $iss_id . ", '" . $subject . "', '" . $descript . "', '" . $user . "', '" . $fechaF . "', 1 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", 0)";

if(!mysqli_query($datos, $query)){

echo 0;

} else {

echo 1;

}


?>