<?php

$muser = $_GET['muser'];
$user = $_GET['user'];
$fechaF = date('Y-m-d h:i:s', strtotime(substr($_GET['fechaF'], 0, 10)));
$stsk_id = $_GET['stsk'];
$subject = $_GET['subject'];
$descript = $_GET['descript'];
$startD = $_GET['startD'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = "INSERT INTO SUBTASKS (STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_TYPE) ";
$query .= "VALUES ( '" . $subject . "', '" . $descript . "', '" . $user . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", 0, 1)";

if(!mysqli_query($datos, $query)){

echo mysqli_error($datos);

} else {

echo "just works";
}


?>