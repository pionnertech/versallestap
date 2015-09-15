<?php

        $muser   = $_GET['muser'];
        $usrs    = $_GET['usrs'];
        $stsk    = $_GET['stsk'];
        $subject = $_GET['subject'];
        $descript= $_GET['descript'];
        $startD  = $_GET['startD'];
        $fechaF  = $_GET['endD'];
        $fac     = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
//get ticket 
$ticket = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(COUNT(ISS_ID)) AS TK FROM ISSUES WHERE ISS_FAC_CODE = " . $fac  ));
//insertar ISSUE patron  ...  todo identico en el stsk and iss
$handle_iss = "INSERT INTO ISSUES (ISS_SUBJECT, ISS_DESCRIP, ISS_CHARGE_USR,ISS_FINISH_DATE, ISS_TYPE, ISS_CTZ, ISS_FAC_CODE, ISS_PROGRESS, ISS_LOCK, ISS_TICKET, ISS_COMENTARY) VALUES ";
$handle_iss .= " ('" . $subject . "', '" . $descript . " ', " . $muser . ", '" . $fechaF . "', 0 , 00000000 , " . $fac . " , 0, 1, 'EX0000" . $ticket['TK'] . "', NULL)";
//insertando
if(!mysqli_query($datos, $handle_iss)){
   mysqli_error($datos);
}

$last = mysqli_insert_id($datos);

//insertando el subtask ...
$handle_stsk = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP, STSK_CHARGE_USR, STSK_STATE, STSK_FINISH_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_ANCIENT_PRO, STSK_LOCK, STSK_TYPE, STSK_TICKET, STSK_RESP, STSK_OVER) VALUES ";

switch ($usrs) {
  case 'Jefaturas':

    $team_admin = mysqli_query($datos,"SELECT USR_ID FROM USERS WHERE (USR_FACILITY = " . $fac . " AND USR_RANGE = 'admin' )");

        while($fila = mysqli_fetch_row($team_admin)){

              $handle_stsk .= " ( " . $last . ", '" . $subject ."' , '" . $descript. "', " . $fila[0] . ", 2, '" . $fechaF. "', " . $muser . " , " . $fac . ", 0, NULL, 1, 2, 1) , ";
        }
  
      $handle_stsk = rtrim($handle_stsk);

    break;
  
  case else:
  $usrs = explode(",", $usrs);

  for($i=0 ;$i< count($usrs); $i++){
       $ui =  mysqli_fetch_assoc(mysqli_query($datos,"SELECT USR_ID as ID FROM USERS WHERE CONCAT(USR_NAME , ' ' ,USR_SURNAME) = " . $usrs[$i] ));
        $handle_stsk .= " ( " . $last . ", '" . $subject ."' , '" . $descript . "', " . $ui['ID'] . ", 2, '" . $fechaF. "', " . $muser . " , " . $fac . ", 0, NULL, 1, 2, 1) , ";
  }
  
       $handle_stsk = rtrim($handle_stsk);
    break;
}


mysqli_query($datos, $handle_stsk);






?>