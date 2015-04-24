<?php

$a = $_GET['usr'];

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$str_query = "SELECT STSK_ID, " .
"STSK_ISS_ID, " .
"STSK_SUBJECT, " .
"STSK_DESCRIP, " .
"STSK_FINISH_DATE AS FECHA_FINAL,  " .
"STSK_START_DATE AS FECHA_INICIAL,  " .
"STSK_TYPE " . 
" FROM SUBTASKS  " . 
"WHERE ( STSK_CHARGE_USR = "  . $a . " AND STSK_LOCK = 1 AND STSK_TYPE = 1) ORDER BY STSK_ID DESC LIMIT 1 ";	

while(true){

$manu = mysqli_fetch_assoc(mysqli_query($datos, $str_query));

sleep(1);

echo "data:" . $manu['STSK_ID'] . "\n";
echo "data:" . $manu['STSK_ISS_ID'] . "\n";
echo "data:" . $manu['STSK_DESCRIP'] . "\n";
echo "data:" . date('d/m/Y', strtotime($manu['FECHA_FINAL'])) . "\n";
echo "data:" . date('d/m/Y', strtotime($manu['FECHA_INICIAL'])) . "\n";
echo "data:" . $manu['STSK_TYPE'] . "\n";
echo "data:" . $manu['STSK_SUBJECT'] . "\n\n"; 

ob_end_flush();
flush();
}

?>

