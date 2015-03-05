<?php 

$a = $_GET['usr'];

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

while(true){
	
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$str_query = "SELECT STSK_DESCRIP FROM `SUBTASKS` WHERE STSK_CHARGE_USR = " . $a . " ORDER BY STSK_START_DATE DESC LIMIT 1";
$manu = mysqli_fetch_assoc(mysqli_query($datos, $str_query));

echo "data:" . $manu['STSK_DESCRIP'] . "\n\n";

ob_end_flush();
flush();
}

?>