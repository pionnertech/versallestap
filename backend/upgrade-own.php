<?php 


$stsk     = $_GET['stsk'];
$iss      = $_GET['iss'];
$percent  = $_GET['percent'];
$subject  = $_GET['subject'];
$descript = $_GET['descript'];
$type     = $_GET['type'];
$fac      = $_GET['fac'];
$muser    = $_GET['muser'];


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

// alterar tablas subtasks y traffic

$query = "UPDATE SUBTASKS SET STSK_CHARGE_USR = " . $muser ." WHERE STSK_ID = " . $stsk . ";";

$query .= "UPDATE SUBTASKS SET  STSK_PROGRESSS = " . $percent ." WHERE STSK_ID = " . $stsk . ";";
$query .= "UPDATE SUBTASKS SET  STSK_ANCIENT_PRO = " . $percent ." WHERE STSK_ID = " . $stsk . ";";
$query .= "UPDATE SUBTASKS SET  STSK_LOCK = 1 WHERE STSK_ID = " . $stsk . ";";
$query .= "UPDATE SUBTASKS SET  STSK_RESP = 1 WHERE STSK_ID = " . $stsk . ";";

$trf = "INSERT INTO TRAFFIC (TRF_STSK_ID, TRF_STSK_SRC_ID , TRF_DESCRIPT, TRF_SUBJECT , TRF_FAC_CODE,  TRF_USER) VALUES ";
$trf = "(" . $stsk .", " . $iss . ", '" . $descript . "', '" . $subject . "', " . $fac .", " . $muser . ")";


if(!mysqli_query($datos, $query)){
	echo mysqli_error($datos);
}

if(!mysqli_query($datos, $trf)){
	echo mysqli_error($datos);
}


?>