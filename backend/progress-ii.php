<?php 

$val = $_GET['val']; // valor de progreso
$id = $_GET['stsk_id']; // stsk id 
$iss_id = $_GET['iss_id']; //  ??????????
$muser = $_GET['muser'];
$subject = $_GET['subject'];
$descript = $_GET['des'];
$date = $_GET['date'];
$fac = $_GET['fac'];
$user = $_GET['user'];
$ticket = $_GET['ticket'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $val . " WHERE (STSK_TYPE = 1 AND STSK_CHARGE_USR = " . $user . " AND  STSK_FAC_CODE = " . $fac . " AND STSK_ISS_ID = " . $iss_id . ");");
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_RESP = 1 WHERE STSK_ID = " . $id);


$min = mysqli_fetch_assoc(mysqli_query($datos, "SELECT MIN(STSK_ID) as MIN FROM SUBTASKS WHERE (STSK_FAC_CODE = " . $fac . " AND STSK_TICKET= '" . $ticket . "')"));


$handler = mysqli_query($datos, "SELECT A.STSK_PROGRESS FROM SUBTASKS A INNER JOIN USERS B ON(B.USR_ID = A.STSK_CHARGE_USR ) WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_FAC_CODE = " . $fac . " AND STSK_TYPE = 1);");

$adition = 0;
$n = 0;

while ($row = mysqli_fetch_row($handler)) {
    $adition += $row[0];
    $n = $n + 1;
}

$setto = ($adition / $n);

mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $setto . " WHERE (STSK_FAC_CODE = " . $fac . " AND STSK_ISS_ID = " . $iss_id . " AND STSK_TYPE = 1 AND STSK_TICKET = '" . $ticket . "');");

$test = mysqli_query($datos, "SELECT STSK_RESP FROM SUBTASKS WHERE (STSK_TICKET = '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . "  AND STSK_RESP = 1)" );

if(mysqli_num_rows($test) !== 0){
	mysqli_query($datos, "INSERT INTO PSEUDO (PSD_USR, PSD_TICKET, PSD_FAC_CODE, PSD_PERCENT) VALUES ( " . $muser . " ,'" . $ticket .  "', " . $fac . ", " . $setto . " )");
}

//detect if a sadmin is the origin
$look = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_RANGE FROM USERS A INNER JOIN SUBTASKS B ON(B.STSK_CHARGE_USR = A.USR_ID AND B.STSK_TICKET = '" . $ticket . "') WHERE B.STSK_ID = " . $min['MIN']));

if($look['USR_RANGE'] == 'sadmin'){

        $pro = mysqli_fetch_assoc(mysqli_query($datos, "SELECT AVG(STSK_PROGRESS) AS PRO FROm SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID) WHERE (STSK_TICKET ='" . $ticket . "' STSK_FAC_CODE = " . $fac . " AND USR_RANGE = 'admin')"));
        
        mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $pro['PRO'] . " WHERE  STSK_ID = " . $min['MIN'] );

        if($pro['PRO'] > 99.95){
                 mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE (STSK_ID = " . $min['MIN'] . ");");

        }

}


//set DONE to local;
if ((int)$val == 100){
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE (STSK_ID = " . $id . " AND STSK_TYPE = 1)" );
}

if ((int)$setto > 99){
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 1 );");
}

//seek the original admin-admin subtask 

$var1 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM `SUBTASKS` WHERE (STSK_TYPE = 1 AND STSK_TICKET = '" . $ticket . "' AND STSK_FAC_CODE =  " . $fac. " AND STSK_CHARGE_USR = STSK_MAIN_USR)"));
$var2 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM `SUBTASKS` WHERE (STSK_ISS_ID = " . $var1['STSK_ISS_ID'] . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 1)"));


$insertar = "INSERT INTO `TRAFFIC_II` (TII_STSK_ID, TII_STSK_SRC_ID, TII_DESCRIPT, TII_SUBJECT, TII_FAC_CODE, TII_ING_DATE, TII_USER) ";
$insertar .= "VALUES (" . $id . ", " . $var2['STSK_ID'] . " , '" . $descript . "', '" . $subject . "', " . $fac . ", '" . $date . "', '" . $user . "');";

if(!mysqli_query($datos, $insertar)){
	echo mysqli_error($datos);
	exit;
} else {
  echo 1;
}




?>