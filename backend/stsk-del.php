<?php


$muser = $_GET['muser'];
$user = $_GET['user'];
$fechaF = $_GET['fechaF'];
$iss_id = $_GET['iss_id'];
$stsk_id = $_GET['stsk'];
$subject = $_GET['subject'];
$descript = $_GET['descript'];
$startD = $_GET['startD'];
$fac = $_GET['fac'];


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE) ";
$query .= "VALUES (" . $iss_id . ", '" . $subject . "', '" . $descript . "', '" . $user . "', '" . $fechaF . "', '" . $startD . "' , '" . $muser . "', " . $fac . ")";

//

if(!mysqli_query($datos, $query)){

echo mysql_errno($datos);

} else {

echo 1;

}


?>