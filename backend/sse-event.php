<?php 

$a = $_GET['usr'];

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

while(true){
	
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$str_query = "SELECT A.STSK_DESCRIP, A.STSK_ID, B.ISS_DESCRIP, B.ISS_ID , CONCAT(C.CTZ_NAMES, " ", C.CTZ_SURNAME1," ", C.CTZ_SURNAME2 ) AS NAME" .
"FROM `SUBTASKS` A " .
"INNER JOIN `ISSUES` B ON(A.STSK_ISS_ID = B.ISS_ID) " .
"INNER JOIN `CITIZENS` C ON(B.ISS_CTZ = C.CTZ_RUT ) WHERE STSK_CHARGE_USR = " . $a . " ORDER BY STSK_START_DATE DESC LIMIT 1";

$manu = mysqli_fetch_assoc(mysqli_query($datos, $str_query));

sleep(1);

echo "data:" . $manu['STSK_DESCRIP'] . "\n";
echo "data:" . $manu['STSK_ID'] . "\n";
echo "data:" . $manu['ISS_DESCRIP'] . "\n";
echo "data:" . $manu['ISS_ID'] . "\n";
echo "data:" . $manu['NAME'] . "\n\n";

ob_end_flush();
flush();
}


$str_query = "SELECT STSK_DESCRIP FROM `SUBTASKS` WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " ORDER BY STSK_START_DATE DESC LIMIT 1";
?>