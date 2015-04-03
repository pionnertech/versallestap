<?php

$a = $_GET['usr'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$str_query = "SELECT STSK_ID, " .
"STSK_ISS_ID, " .
"STSK_SUBJECT, " .
"STSK_DESCRIP, " .
"STSK_FINISH_DATE AS FECHA_FINAL,  " .
"STSK_START_DATE AS FECHA_INICIAL,  " .
"STSK_TYPE " . 
" FROM SUBTASKS  " . 
"WHERE ( STSK_CHARGE_USR = "  . $a . " AND STSK_LOCK = 1 AND STSK_TYPE = 1 AND STSK_MAIN_USR <> STSK_CHARGE_USR) ORDER BY STSK_ID DESC LIMIT 1 ";	

$manu = mysqli_fetch_assoc(mysqli_query($datos, $str_query));

echo $manu['STSK_ID'] . "|";
echo $manu['STSK_ISS_ID'] . "|";
echo $manu['STSK_DESCRIP'] . "|";
echo date('d/m/Y', strtotime($manu['FECHA_FINAL'])) . "|";
echo date('d/m/Y', strtotime($manu['FECHA_INICIAL'])) . "|";
echo $manu['STSK_TYPE'] . "|";



?>