<?php header('Content-Type: text/html; charset=utf-8');

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = mysqli_query($datos, "SELECT STSK_DESCRIP, STSK_START_DATE, STSK_FINISH_DATE FROM SUBTASKS WHERE STSK_FAC_CODE = 10000");

$i = 0;
$count = mysqli_num_rows($query);

echo "[";

while ($fila = mysqli_fetch_row($query)){
	echo "{ \"f1\":\"" . $fila[0] . "\",";
	echo "\"f2\":\"" . $fila[1] . "\",";
	echo "\"f2\":\"" . $fila[1] . "\"}";

	 if($i < $count -1){
        echo ",";
	 }
}

echo "]";