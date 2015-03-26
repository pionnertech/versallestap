<?php 

$a = $_GET['usr'];

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$str_query = "SELECT A.STSK_DESCRIP, A.STSK_ID, B.ISS_DESCRIP, B.ISS_ID , CONCAT(C.CTZ_NAMES, ' ' , C.CTZ_SURNAME1, ' ' , C.CTZ_SURNAME2 ) AS NAME " .
"FROM `SUBTASKS` A " .
"INNER JOIN `ISSUES` B ON(A.STSK_ISS_ID = B.ISS_ID) " .
"INNER JOIN `CITIZENS` C ON(B.ISS_CTZ = C.CTZ_RUT ) WHERE STSK_CHARGE_USR = " . $a . " ORDER BY STSK_ID DESC LIMIT 1";

while(true){
	
$manu = mysqli_fetch_assoc(mysqli_query($datos, $str_query));

sleep(1);

echo $manu['STSK_DESCRIP'] . "|";
echo $manu['STSK_ID'] . "|";
echo $manu['ISS_DESCRIP'] . "|";
echo $manu['ISS_ID'] . "|";
echo $manu['NAME'];

ob_end_flush();
flush();
}


?>