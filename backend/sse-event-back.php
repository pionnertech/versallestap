<?php

$a = $_GET['usr'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$str_query = "SELECT A.STSK_ID, " .
"A.STSK_ISS_ID, " .
"A.STSK_SUBJECT, " .
"A.STSK_DESCRIP, " .
"B.ISS_DESCRIP, " .
"CONCAT(C.CTZ_NAMES, ' ' , C.CTZ_SURNAME1, ' ', C.CTZ_SURNAME2) AS NAME , " .
"A.STSK_FINISH_DATE AS FECHA_FINAL,  " .
"A.STSK_START_DATE AS FECHA_INICIAL,  " .
"A.STSK_TYPE, " . 
"C.CTZ_TEL, " .
"C.CTZ_ADDRESS " .
" FROM SUBTASKS A LEFT JOIN ISSUES B ON(B.ISS_ID = A.STSK_ISS_ID) LEFT JOIN CITIZENS C ON (B.ISS_CTZ = C.CTZ_RUT) " . 
"WHERE ( STSK_CHARGE_USR = "  . $a . " AND STSK_LOCK = 1) ORDER BY STSK_ID DESC LIMIT 1 ";	

$manu = mysqli_fetch_assoc(mysqli_query($datos, $str_query));
echo $manu['STSK_ID'] . "|";
echo $manu['STSK_ISS_ID'] . "|";
echo $manu['STSK_DESCRIP'] . "|";
echo $manu['ISS_DESCRIP'] . "|";
echo $manu['NAME'] . "|";
echo date('d/m/Y', strtotime($manu['FECHA_FINAL'])) . "|";
echo date('d/m/Y', strtotime($manu['FECHA_INICIAL'])) . "|";
echo $manu['STSK_TYPE'] . "|" ;
echo $manu['CTZ_TEL'] . "|";
echo $manu['CTZ_ADDRESS'];


?>
