<?php 

$stsk_id = $_GET['stsk_id'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

if(!mysqli_query($datos,"UPDATE SUBTASKS SET STSK_LOCK = 1 WHERE STSK_ID= " . $stsk_id )){
	echo 0
} else {
	echo 1;

}


?>