<?php

$usr = $_GET['usr'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
$query = "SELECT A.STSK_PROGRESS, A.STSK_ANCIENT_PRO, A.STSK_ID, A.STSK_ISS_ID, A.STSK_TYPE, B.USR_RANGE FROM SUBTASKS  A INNER JOIN USERS B ON (B.USR_ID = A.STSK_CHARGE_USR) WHERE (STSK_MAIN_USR = "  . $usr . " AND (STSK_PROGRESS != STSK_ANCIENT_PRO OR STSK_PROGRESS IS NULL))";

 $row = mysqli_query($datos, $query);

$sp = array();
$i = 0;

 while ($fila = mysqli_fetch_row($row)) {

 	echo $fila[5] = $sp[$i];
 	$i++;
 }


echo "   especial : " . $sp[$i];



?>