<?php

$iss_id = $_GET['iss_id'];
$fac = $_GET['fac'];
$chusr = $_GET['chusr'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


//seek data
$id_s = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR = STSK_MAIN_USR)"));


$str_traffic = "SELECT A.TRF_STSK_SRC_ID,  " .
"A.TRF_SUBJECT, " . 
"A.TRF_DESCRIPT, " . 
"A.TRF_ING_DATE, " . 
"A.TRF_USER, CONCAT(B.USR_NAME , ' ' ,  B.USR_SURNAME)  FROM TRAFFIC A INNER JOIN USERS B ON(A.TRF_USER = B.USR_ID) " . 
"WHERE (TRF_FAC_CODE = " . $fac . " AND TRF_STSK_SRC_ID = " . $id_s['STSK_ID'] . ") ORDER BY TRF_USER, TRF_ING_DATE;";

$handler = mysqli_query($datos, $str_traffic);
$num = mysqli_num_rows($handler);

$i= 0;
echo "{\"datos\":[";

while($row = mysqli_fetch_row($handler)){

	echo "{\"user\":\"" . str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($row[5])))) . "\", ";
	echo "\"subject\":\"" . $row[1] . "\",";
	echo "\"des\":\"" . $row[2] . "\",";
	echo "\"date\":\"". date("d/m/Y h:i:s", strtotime($row[3])) . "\"}";
    
    $i = $i + 1;

	 if($i < $num){
        echo ",";
	 }
}

echo "]}";

?>