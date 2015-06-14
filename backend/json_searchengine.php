<?php 


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = mysqli_query($datos, "SELECT STSK_DESCRIP, STSK_START_DATE, STSK_FINISH_DATE FROM SUBTASKS WHERE (STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_TYPE = 0 AND STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . ")");

$i = 0;

$count = mysqli_num_rows($query);

echo "var subjects = [";

while ($fila = mysqli_fetch_row($query)){

	echo "'" .  $fila[0] . "'";

		 if($i < $count -1){

        echo ",";
	 }

	 $i = $i + 1;
}

echo "]";

?>