<?php


$iss_stsk    = $_GET['st'];
$percent     = $_GET['per'];
$stsk_src_id = $_GET['srcid'];

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

while (true) {
	
$handler    = mysqli_query($datos, "SELECT SUM(STSK_PROGRESS) FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_stsk . " AND STSK_CHARGE_USR != STSK_MAIN_USR) GROUP BY STSK_CHARGE_USR ");
$count      = mysqli_num_rows($handler);

while ($fila = mysqli_fetch_row($handler)){
	$sum += $fila[0];
}

$ctp   = (($sum * 100) / (100 * $count));

$classText = "";

if ($ctp >= 99.9 ){

   $classText = "FINALIZADO";

}

//GEt the Last User that grow up his progress


$query_usr = mysqli_query($datos, "SELECT CONCAT(A.USR_NAME, ' ', A.USR_SURNAME), A.TRF_SUBJECT, A.TRF_DESCRIPT FROM TRAFFIC A INNER JOIN USERS B ON(A.TRF_USER = B.USR_ID) WHERE A.TRF_STSK_SRC_ID = " . $stsk_src_id . " ORDER BY TRF_ID DESC LIMIT 1" );
$user      = mysqli_fetch_assoc($query_usr);

sleep(1);

echo "data :" . str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($user['TRF_USER'])))).  "\n";
echo "data :" . $user['TRF_SUBJECT'] .  "\n";
echo "data :" . $user['TRF_DESCRIPT'] .  "\n";
echo "data :" . $ctp .  "\n";
echo "data :" . $classText .  "\n\n";

ob_end_flush();
flush();

$sum = 0;
}


//defina los pasos a seguir

/*

obtener el porcentaje unitario.
obtener el porcentaje total ->  get the total percentaje of members
take these values and  make the total percentaje of ISS

100 60 40 90 = 290 = x
400          = 400 =  100%
               290 =  x

if percenjage  is less than  total then class  "EN CURSO"
else "FINALIZADO"
70% 

*/





?>