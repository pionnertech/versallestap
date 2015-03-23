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
$type = $_GET['type'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_TYPE) ";
$query .= "VALUES (" . $iss_id . ", '" . $subject . "', '" . $descript . "', '" . $user . "', '" . $fechaF . "', 1 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", 0, 0)";

if(!mysqli_query($datos, $query)){

echo 0;

} else {

 $handler = mysqli_query($datos, "SELECT CONCAT(B.USR_NAME, '', B.USR_SURNAME) FROM SUBTASKS A INNER JOIN USERS B ON(B.USR_ID = A.STSK_CHARGE_USR) WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR != STSK_MAIN_USR )");
 while ($fila1v = mysqli_fetch_row($handler)){
 	echo $fila1v[0] . ",";
 }

}


?>