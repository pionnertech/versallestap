<?php

$muser = $_GET['muser'];
$user = $_GET['user'];
$fechaF = date('Y-m-d h:i:s', strtotime(substr($_GET['fechaF'], 0, 10)));
$iss_id = $_GET['iss_id'];
$stsk_id = $_GET['stsk'];
$subject = $_GET['subject'];
$descript = $_GET['descript'];
$startD = $_GET['startD'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS) ";
$query .= "VALUES (" . $iss_id . ", '" . $subject . "', '" . $descript . "', '" . $user . "', '" . $fechaF . "', 1 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", 0)";

if(!mysqli_query($datos, $query)){

echo 0;

} else {

 $handler = mysqli_query($datos, "SELECT STSK_CHARGE_USR WHERE STSK_ISS_ID = " . $iss_id);
 while ($fila1v = mysqli_fetch_row($handler)){
 	echo $fila1v .",";
 }


}


?>