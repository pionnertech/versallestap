<?php 

$val = $_GET['val']; // valor de progreso
$id = $_GET['stsk_id']; // stsk id 
$iss_id = $_GET['iss_id']; //  ??????????
$muser = $_GET['muser'];
$subject = $_GET['subject'];
$descript = $_GET['des'];
$date = $_GET['date'];
$fac = $_GET['fac'];
$user = $_GET['user'];
$ticket = $_GET['ticket'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $val . " WHERE (STSK_TYPE = 1 AND STSK_CHARGE_USR = " . $user . " AND  STSK_FAC_CODE = " . $fac . " AND STSK_ISS_ID = " . $iss_id . ");");

$handler = mysqli_query($datos, "SELECT STSK_PROGRESS FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR <> STSK_MAIN_USR AND STSK_TYPE = 1);");

$adition = 0;
$n = 0;

while ($row = mysqli_fetch_row($handler)) {
    $adition += $row[0];
    $n = $n + 1;
}

$setto = ($adition / $n);

mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $setto . " WHERE (STSK_FAC_CODE = " . $fac . " AND STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR = " . $muser . " AND STSK_TYPE = 1 AND STSK_TICKET = '" . $ticket . "');");
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $setto . " WHERE (STSK_TICKET = '" . $ticket . "' AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 1 AND STSK_FAC_CODE = " . $fac . " )");

//set DONE to local;
if ((int)$val == 100){
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE (STSK_ID = " . $id . " AND STSK_TYPE = 1)" );
}

if ((int)$setto > 99){
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 1 );");
}

//seek the original admin-admin subtask 

$var1 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM `SUBTASKS` WHERE (STSK_ID = " . $id . " AND STSK_TYPE = 1)"));
$var2 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM `SUBTASKS` WHERE (STSK_ISS_ID = " . ((int)$var1['STSK_ISS_ID']-1) . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 1)"));


$insertar = "INSERT INTO `TRAFFIC_II` (TII_STSK_ID, TII_STSK_SRC_ID, TII_DESCRIPT, TII_SUBJECT, TII_FAC_CODE, TII_ING_DATE, TII_USER) ";
$insertar .= "VALUES (" . $id . ", " . $var2['STSK_ID'] . " , '" . $descript . "', '" . $subject . "', " . $fac . ", '" . $date . "', '" . $user . "');";

if(!mysqli_query($datos, $insertar)){
	echo mysqli_error($datos);
	exit;
} else {
  echo 1;
}




?>