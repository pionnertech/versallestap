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

$query1= "UPDATE SUBTASKS SET  STSK_PROGRESS = " . $percent ." WHERE STSK_ID = " . $stsk . ";";
$query2= "UPDATE SUBTASKS SET  STSK_ANCIENT_PRO = " . $percent ." WHERE STSK_ID = " . $stsk . ";";
$query3= "UPDATE SUBTASKS SET  STSK_LOCK = 1 WHERE STSK_ID = " . $stsk . ";";
$query4= "UPDATE SUBTASKS SET  STSK_RESP = 1 WHERE STSK_ID = " . $stsk . ";";
$query5= "UPDATE ISSUES   SET  ISS_PROGRESS = " . $percent . " WHERE ISS_ID = " . $iss;

$trf = "INSERT INTO TRAFFIC (TRF_STSK_ID, TRF_STSK_SRC_ID , TRF_DESCRIPT, TRF_SUBJECT , TRF_FAC_CODE,  TRF_USER) VALUES ";
$trf .= "(" . $stsk .", " . (int)$stsk-1 . ", '" . $descript . "', '" . $subject . "', " . $fac .", " . $muser . ")";

if(!mysqli_query($datos, $trf)){
	echo mysqli_error($datos);
}

if(!mysqli_query($datos, $query)){
	echo mysqli_error($datos);
}
if(!mysqli_query($datos, $query1)){
	echo mysqli_error($datos);
}
if(!mysqli_query($datos, $query2)){
	echo mysqli_error($datos);
}
if(!mysqli_query($datos, $query3)){
	echo mysqli_error($datos);
}
if(!mysqli_query($datos, $query4)){
	echo mysqli_error($datos);
}
if(!mysqli_query($datos, $query5)){
	echo mysqli_error($datos);
}



echo $percent;

if((int)$percent > 99){
	mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE STSK_ID = " . $stsk . ";");
	mysqli_query($datos, "UPDATE ISSUES SET ISS_STATE = 5 WHERE ISS_ID = " . $iss);
}


?>