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

$handler = mysqli_query($datos, "SELECT STSK_PROGRESS FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR <> STSK_MAIN_USR);");

$adition = 0;
$n = 0;

while ($row = mysqli_fetch_row($handler)) {
    $adition += $row[0];
    $n = $n + 1;
}

$setto = ($adition / $n);

mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS =  " . $setto . " WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR = STSK_MAIN_USR );");

//set DONE to local;
if ((int)$val == 100){
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE STSK_ID = " . $id );
}

if ((int)$setto > 99){
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR = STSK_MAIN_USR );");
}





//seek the original admin-admin subtask 

$var1 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM `SUBTASKS` WHERE STSK_ID = " . $id));
$var2 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM `SUBTASKS` WHERE (STSK_ISS_ID = " . $var1['STSK_ISS_ID'] . " AND STSK_CHARGE_USR = STSK_MAIN_USR)"));


$insertar = "INSERT INTO `TRAFFIC` (TRF_STSK_ID, TRF_STSK_SRC_ID, TRF_DESCRIPT, TRF_SUBJECT, TRF_FAC_CODE, TRF_ING_DATE, TRF_USER) ";
$insertar .= "VALUES (" . $id . ", " . $var2['STSK_ID'] . " , '" . $descript . "', '" . $subject . "', " . $fac . ", '" . $date . "', '" . $user . "');";

if(!mysqli_query($datos, $insertar)){
	echo "status failed";
	exit;
}

//añadir progreso a la audiencia
$query = mysqli_query($datos, "SELECT STSK_PROGRESS FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_MAIN_USR <> STSK_CHARGE_USR);");


$suma = 0;
$count = mysqli_num_rows($query);

while ( $fila5 = mysqli_fetch_row($query) ){

       $suma += $fila5[0];
}

$upgrade = ($suma / $count);

if(!mysqli_query($datos, "UPDATE ISSUES SET ISS_PROGRESS = " . (int)$upgrade . " WHERE ISS_ID = " . $iss_id . ";")){
    
 echo 0;

} else {
  
  if ($upgrade > 99.5){
  	    if(!mysqli_query($datos, "UPDATE ISSUES SET ISS_STATE = 5 WHERE ISS_ID = " . $iss_id . ";")){
         	echo 0;
   }

  }

echo 1;

   
}


?>