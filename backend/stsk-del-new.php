<?php

$muser = $_GET['muser'];
$user = $_GET['user'];
$fechaF = date('Y-m-d h:i:s', strtotime(substr($_GET['fechaF'], 0, 10)));
$iss_id = $_GET['iss_id'];
$stsk_id = $_GET['stsk'];
$subject = $_GET['subject'];
$descript = $_GET['descript'];
$startD = $_GET['startD'];
$fac = $_GET['fac'];
$type = $_GET['type'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

if(!mysqli_query($datos, "UPDATE SUBTASKS SET STSK_RESP = 0 WHERE (STSK_TYPE= 0 AND STSK_FAC_CODE =" . $fac ." AND STSK_ISS_ID =" . $iss_id . ")" )){
  exit mysqli_error($datos);
}

// ticket  plus

$ticket = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_TICKET FROm SUBTASKS  WHERE STSK_ID =" . $stsk_id));

//processing array 
  $dept = mysqli_fetch_assoc(mysqli_query($datos,"SELECT USR_DEPT FROM USERS WHERE (USR_ID = " . $muser . " AND USR_FACILITY= " . $fac . ")"));

if($user == "Mi Departamento"){

    $uteam = mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE (USR_DEPT = '" . $dept['USR_DEPT'] . "' AND USR_FACILITY =  "  . $fac . " AND USR_RANGE <> 'admin' )" );

    while($fila = mysqli_fetch_row($uteam)){
        $query = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_TYPE, STSK_LOCK, STSK_TICKET) ";
        $query .= "VALUES (" . $iss_id . ", '" . $subject . "', '" . $descript . "', " . $fila[0] . ", '" . $fechaF . "', 1 ,  '" . $startD . "' , " . $muser . ", " . $fac . ", 0, 0, 1, '" . $ticket['STSK_TICKET']. "')";
         mysqli_query($datos, $query);
         echo $fila[0] . "|";
         }
} else {
//transform user name 
  $uteam_array = explode( "," ,$user);

  for($i= 0; $i < count($uteam_array); $i++ ){
  	  $usr_id_q = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE (CONCAT(USR_NAME, ' ' , USR_SURNAME) = '" . strtoupper($uteam_array[$i]) . "' AND USR_FACILITY = " . $fac .")"));
      
       $query = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_TYPE, STSK_LOCK, STSK_TICKET) ";
       $query .= "VALUES (" . $iss_id . ", '" . $subject . "', '" . $descript . "', '" . $usr_id_q['USR_ID'] . "', '" . $fechaF . "', 1 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", 0, 0, 1, '" . $ticket['STSK_TICKET'] . "')";
  	   mysqli_query($datos, $query);
  	   echo $usr_id_q['USR_ID'] . "|";
  }

}




?>