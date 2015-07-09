
<?php

$a = $_GET['usr'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$str_query = "SELECT A.STSK_ID, " .
"A.STSK_ISS_ID, " .
"A.STSK_SUBJECT, " .
"A.STSK_DESCRIP, " .
"A.STSK_FINISH_DATE AS FECHA_FINAL,  " .
"A.STSK_START_DATE AS FECHA_INICIAL,  " .
"A.STSK_TYPE, " . 
"A.STSK_PROGRESS, " .
"A.STSK_MAIN_USR, " .
"CONCAT(B.USR_NAME, ' ', B.USR_SURNAME),  " .
"A.STSK_TICKET " .
"FROM SUBTASKS  A INNER JOIN USERS B ON(B.USR_ID = A.STSK_MAIN_USR) " . 
"WHERE ( STSK_CHARGE_USR = "  . $a . " AND STSK_LOCK = 1 AND STSK_TYPE = 1 AND STSK_MAIN_USR <> STSK_CHARGE_USR AND STSK_PROGRESS IS NULL ) ORDER BY STSK_ID DESC";	

$hand = mysqli_query($datos, $str_query);

if(mysqli_num_rows($hand) !== 0){

while ($manu = mysqli_fetch_row($hand)){

echo $manu[0] . "|" . $manu[1] . "|" . $manu[3] . "|'" . date('d/m/Y', strtotime($manu[4])) . "'|" . date('d/m/Y', strtotime($manu[5])) . "|" . $manu[6] . "|"  . $manu[8] . "|" . str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($manu[9])))) . "|" . $manu[10]  ;

   }

   $hand2 = mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = STSK_ANCIENT_PRO WHERE (STSK_CHARGE_USR = "  . $a . " AND STSK_LOCK = 1 AND STSK_TYPE = 1 AND STSK_MAIN_USR <> STSK_CHARGE_USR AND STSK_PROGRESS IS NULL)");
}


?>




