<?php


$iss_stsk = $_GET['st'];
$percent  = $_GET['per'];

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

while (true) {
	
$handler    = mysqli_query($datos, "SELECT SUM(STSK_PROGRESS) FROM SUBTASKS WHERE STSK_ISS_ID = " . $iss_stsk . " GROUP BY STSK_CHARGE_USR ");


while ($fila = mysqli_fetch_row($handler)){
	$sum += $fila[0];
}

$ctp   = ($sum * 100) / (100 * $count);

$classText = "";

if ($ctp >= 99.9 ){

   $classText = "FINALIZADO";

}

//GEt the Last User that grow up his progress


$query_usr = mysqli_query($datos, "SELECT TRF_USER FROM TRAFFIC WHERE TRF_STSK_ID = " . $stsk_trf . " ORDER BY TRF_ING_DATE DESC LIMIT 1" );
$user      = mysqli_fetch_assoc($query_usr);

sleep(1);

echo "data :" . $user['TRF_USER'] .  "\n";
echo "data :" . $ctp .  "\n";
echo "data :" . $classText .  "\n\n";

ob_end_flush();
flush();
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