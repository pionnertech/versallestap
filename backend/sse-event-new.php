<?php 

$a = $_GET['usr'];

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$str_query = "SELECT A.STSK_DESCRIP, A.STSK_ID, B.ISS_DESCRIP, B.ISS_ID , CONCAT(C.CTZ_NAMES, ' ' , C.CTZ_SURNAME1, ' ' , C.CTZ_SURNAME2 ) AS NAME, C.CTZ_TEL, C.CTZ_ADDRESS, A.STSK_FINISH_DATE, A.STSK_TICKET, C.CTZ_GEOLOC " .
"FROM `SUBTASKS` A " .
"INNER JOIN `ISSUES` B ON(A.STSK_ISS_ID = B.ISS_ID) " .
"INNER JOIN `CITIZENS` C ON(B.ISS_CTZ = C.CTZ_RUT ) WHERE STSK_CHARGE_USR = " . $a . " ORDER BY STSK_ID DESC LIMIT 1";


while(true){
	
$manu = mysqli_fetch_assoc(mysqli_query($datos, $str_query));

sleep(1);

echo "data:" . $manu['STSK_DESCRIP'] . "\n";
echo "data:" . $manu['STSK_ID'] . "\n";
echo "data:" . $manu['ISS_DESCRIP'] . "\n";
echo "data:" . $manu['ISS_ID'] . "\n";
echo "data:" . $manu['NAME'] . "\n";
echo "data:" . $manu['CTZ_TEL'] . "\n";
echo "data:" . $manu['CTZ_ADDRESS'] . "\n";
echo "data:" . str_replace("-","/" , date("d/m/Y", strtotime(substr($manu['STSK_FINISH_DATE'], 0, 10)))) . "\n";
echo "data:" . $manu['CTZ_GEOLOC'] . "\n";
echo "data:" . $manu['STSK_TICKET'] . "\n\n";


ob_end_flush();
flush();

}


?>