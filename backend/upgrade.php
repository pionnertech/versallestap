<?php 

$val = $_GET['val'];
$id = $_GET['stsk_id'];
$iss_id = $_GET['iss_id'];
$user = $_GET['mmx'];
$subject = $_GET['subject'];
$descript = $_GET['des'];
$date = $_GET['date'];
$fac = $_GET['fac'];



$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS =  " . $val . " WHERE STSK_ID = " . $id . " ;");


$insertar = "INSERT INTO TRAFFIC (TRF_STSK_ID, TRF_DESCRIPT, TRF_SUBJECT, TRF_FAC_CODE, TRF_ING_DATE, TRF_USER) ";
$insertar .= "VALUES (" . $id . ", '" . $descript . "', '" . $subject . "', " . $fac . ", '" . $date . "', '" . $user . "');";

if(!mysqli_query($datos, $insertar)){
	echo "status failed";
	exit;
}


//añadir progreso a la audiencia
$query = mysqli_query($datos, "SELECT STSK_PROGRESS FROM SUBTASKS WHERE STSK_ISS_ID = " . $iss_id . ";");

$i = 0;
$suma = 0;
$count = mysqli_num_rows($query);

while ( $fila5 = mysqli_fetch_row($query) ){

       $fila5[$i] += $suma;

       $i++;
}


$upgrade = ($suma / $count);


if(!mysqli_query($datos, "UPDATE ISSUES SET ISS_PROGRESS =" . $upgrade . " WHERE ISS_ID = " . $iss_id . ";")){
  
 echo 0;

} else {


   echo 1;

}


?>