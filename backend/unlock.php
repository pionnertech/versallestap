<?php 

$stsk_id = $_GET['stsk_id'];
$iss_id = $_GET['iss_id'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

if(!mysqli_query($datos,"UPDATE SUBTASKS SET STSK_LOCK = 1 WHERE (STSK_ID = " . $stsk_id . " AND STSK_FAC_CODE = " . $fac . " AND STSK_TYPE = 1)" )){
	echo 0;
} else {


if(isset($iss_id)){
	 $iss_id2 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROm SUBTASKS WHERE (STSK_ID = " . $stsk_id . " AND STSK_FAC_CODE = " . $fac . ")"));
     mysqli_query($datos,"UPDATE SUBTASKS SET STSK_LOCK = 1 WHERE (STSK_ISS_ID = " .  $iss_id2['STSK_ISS_ID'] . " AND STSK_FAC_CODE = " . $fac . ")");
}
	echo 1;

}

?>