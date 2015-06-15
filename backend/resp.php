<?php
$muser = $_GET['muser'];
$stsk_id = $_GET['stsk'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$rstate = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_RESP FROM USERS WHERE STSK_ID = " . $stsk_id));

if($rstate['STSK_RESP'] == 1){
    $haarp = mysqli_query($datos, "UPDATE SUBTASKS SET STSK_RESP = 0 WHERE (STSK_CHARGE_USR= " . $muser . " AND STSK_FAC_CODE = " . $fac . ")");
} else {
	$haarp = mysqli_query($datos, "UPDATE SUBTASKS SET STSK_RESP = 1 WHERE (STSK_CHARGE_USR= " . $muser . " AND STSK_FAC_CODE = " . $fac . ")");
}

if($haarp){

echo "modified";

} else {

	echo "an error as occurred <br/>";
}

?>