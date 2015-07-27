<?php 

$val      = $_GET['val'];
$id       = $_GET['stsk_id'];
$iss_id   = $_GET['iss_id'];
$user     = $_GET['user'];
$muser    = $_GET['muser'];
$subject  = $_GET['subject'];
$descript = $_GET['des'];
$date     = $_GET['date'];
$fac      = $_GET['fac'];
$argument = $_GET['argument'];
$ticket   = $_GET['ticket'];

$setto = 0;
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

if($argument == 0){

mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS =  " . $val . " WHERE STSK_ID = " . $id . " ;");

$handler = mysqli_query($datos, "SELECT STSK_PROGRESS FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR <> STSK_MAIN_USR AND STSK_TYPE = 0);");

$adition = 0;
$n = 0;

while ($row = mysqli_fetch_row($handler)) {
    $adition += $row[0];
    $n = $n + 1;
}

$setto = ($adition / $n);

mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS =  " . $setto . " WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 0);");

//set DONE to local;
if ((int)$val == 100){
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE STSK_ID = " . $id );
}

if ((int)$setto > 99){
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 0);");
}


//seek the original admin-admin subtask 

$var1 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM `SUBTASKS` WHERE (STSK_ID = " . $id . " AND STSK_TYPE = 0)"));
$var2 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM `SUBTASKS` WHERE (STSK_ISS_ID = " . $var1['STSK_ISS_ID'] . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 0)"));


$insertar = "INSERT INTO `TRAFFIC` (TRF_STSK_ID, TRF_STSK_SRC_ID, TRF_DESCRIPT, TRF_SUBJECT, TRF_FAC_CODE, TRF_ING_DATE, TRF_USER) ";
$insertar .= "VALUES (" . $id . ", " . $var2['STSK_ID'] . " , '" . $descript . "', '" . $subject . "', " . $fac . ", '" . $date . "', '" . $user . "');";



if(!mysqli_query($datos, $insertar)){
	echo "status failed";
	exit;
}

//añadir progreso a la audiencia
$query = mysqli_query($datos, "SELECT STSK_PROGRESS FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_MAIN_USR <> STSK_CHARGE_USR AND STSK_TYPE = 0);");


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

// break 


} else {

 mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $val . " WHERE (STSK_TYPE = 1 AND STSK_FAC_CODE = " . $fac . " AND STSK_ID = " . $id . ");");


$handler = mysqli_query($datos, "SELECT STSK_PROGRESS FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_ISS_ID <> STSK_ID AND STSK_TYPE = 1)");

$adition = 0;
$n = 0;

while ($row = mysqli_fetch_row($handler)) {
    $adition += $row[0];
    $n = $n + 1;
}

$setto = ($adition / $n);
//cuando se tiene mas de un admin controlando... esto es valido , de lo contrario pasa corbata
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $setto . " WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR <> STSK_MAIN_USR AND STSK_ID = STSK_ISS_ID AND STSK_TICKET= '" . $ticket . "' AND STSK_TYPE = 1);");
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $setto . " WHERE (STSK_MAIN_USR = STSK_CHARGE_USR AND STSK_MAIN_USR = "  . $muser . " AND STSK_TICKET= '" . $ticket . "' AND STSK_TYPE = 1 AND STSK_FAC_CODE = " . $fac . ");");
// check 


//set DONE to local;
if ((int)$val == 100){
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE (STSK_ID = " . $id . " AND STSK_TYPE = 1)" );
}

if ((int)$setto > 99){

mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE (STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TICKET = '" . $ticket . "' AND STSK_TYPE = 1);");
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE ( STSK_MAIN_USR <> STSK_CHARGE_USR AND STSK_ID = STSK_ISS_ID AND STSK_TICKET = '" . $ticket . "' AND STSK_TYPE = 1 AND STSK_FAC_CODE = " . $fac .");");
}

$test = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_RESP FROM SUBTASKS WHERE (STSK_TICKET = '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . " AND STSK_MAIN_USR = " . $muser . " AND STSK_RESP = 0)" ));

if($test['STSK_RESP'] == 0  ){
  mysqli_query($datos, "INSERT INTO PSEUDO (PSD_USR, PSD_TICKET, PSD_FAC_CODE, PSD_PERCENT) VALUES ( " . $muser . " ,'" . $ticket .  "', " . $fac . ", " . $setto . " )");
}
//seek the original admin-admin subtask 
$var1 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM `SUBTASKS` WHERE (STSK_ID = " . $id . " AND STSK_TYPE = 1)"));
$var2 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM `SUBTASKS` A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID) WHERE (STSK_ISS_ID = " . $var1['STSK_ISS_ID'] ." AND B.USR_RANGE = 'admin' AND STSK_TYPE = 1)"));

$insertar = "INSERT INTO `TRAFFIC_II` (TII_STSK_ID, TII_STSK_SRC_ID, TII_DESCRIPT, TII_SUBJECT, TII_FAC_CODE, TII_ING_DATE, TII_USER) ";
$insertar .= "VALUES (" . $id . ", " . $var2['STSK_ID'] . " , '" . $descript . "', '" . $subject . "', " . $fac . ", '" . $date . "', '" . $user . "');";

if(!mysqli_query($datos, $insertar)){
  echo mysqli_error($datos);
  exit;
} else {
  echo 1;
}
}

?>