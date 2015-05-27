<?php 

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = mysqli_query($datos, "SELECT STSK_DESCRIPT, STSK_START_DATE, STSK_FINISH_DATE FROM SUBTASKS WHERE (STSK_FAC_CODE = 10000)");

$i = 0;

echo "[";

while ($fila = mysqli_fetch_row($query)){
	echo "{ \"f1\":\"" . $fila[0] . "\",";
	echo "\"f2\":\"" . $fila[1] . "\",";
	echo "\"f2\":\"" . $fila[1] . "\"}";
	
	 if($i < count($fila)-1){
        echo ",";
	 }
}

echo "]";