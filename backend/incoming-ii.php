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
"WHERE ( STSK_CHARGE_USR = "  . $a . " AND STSK_LOCK = 1 AND STSK_TYPE = 1 AND STSK_MAIN_USR <> STSK_CHARGE_USR AND STSK_ANCIENT <> STSK_PROGRESS) ORDER BY STSK_ID DESC";	

$hand = mysqli_query($datos, $str_query);

while ($manu = mysqli_fetch_row($hand)){

echo $manu[0] . "|" . $manu[1] . "|" . $manu[3] . "|" . date('d/m/Y', strtotime($manu[4])) . "|" . date('d/m/Y', strtotime($manu[5])) . "|" . $manu[6] . "|\n";

}




?>