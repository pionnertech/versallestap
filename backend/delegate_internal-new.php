<?php
$muser       = $_GET['muser'];
$user_id     = $_GET['user'];
$fechaF      = date('Y-m-d h:i:s', strtotime(str_replace("/","-",$_GET['fechaF'])));
$stsk_id     = $_GET['stsk'];
$subject     = $_GET['subject'];
$descript    = $_GET['descript'];
$startD      = $_GET['startD'];
$fac         = $_GET['fac'];
$stsk_src_id = $_GET['main_stsk'];
$keyfile     = $_GET['keyfile'];
$ticket      = $_GET['ticket'];

$number      = 0;
$muser_range = "";

$outcome = "";
 $dir = "/var/www/html/" . $fac . "/";


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$dept = mysqli_fetch_assoc(mysqli_query($datos,"SELECT USR_DEPT FROM USERS WHERE (USR_ID = " . $muser . " AND USR_FACILITY= " . $fac . ")"));

$team = mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE (USR_DEPT = '" . $dept['USR_DEPT'] . "' AND USR_FACILITY = " . $fac . " AND USR_RANGE <> 'admin')");

if(!isset($ticket) || $ticket == ""){
$ngnix = mysqli_fetch_assoc(mysqli_query($datos, "SELECT COUNT(STSK_ID) AS TICKET FROM SUBTASKS WHERE (STSK_TYPE = 1 AND STSK_FAC_CODE = " . $fac . ")" ));
$ticket = "IN0000" . $ngnix['TICKET'];
}

if($stsk_src_id == 0){

$query_es  = "INSERT INTO SUBTASKS (STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_ANCIENT_PRO, STSK_TYPE, STSK_LOCK, STSK_TICKET) ";
$query_es .= "VALUES ('" . $subject . "', '" . $descript . "', " . $muser . " , '" . $fechaF . "', 2 ,  '" . $startD . "' , " . $muser . " , " . $fac . ", 0, 0,  1, 1, '" . $ticket . "')";

//echo $query_es . "<br />";
  $hds = mysqli_query($datos, $query_es);

    if($hds){

      $number = mysqli_insert_id($datos);

        if(mysqli_query($datos , "UPDATE SUBTASKS SET STSK_ISS_ID = " . $number . " WHERE STSK_ID = " . $number )){
        
            } else {

        echo mysqli_error($datos);

        }

    } else {

    echo mysqli_error($hds);

    exit ;

    }

} else {

  $number = $stsk_src_id;
  
  mysqli_query($datos, "UPDATE SUBTASKS SET STSK_RESP = 0 WHERE (STSK_ISS_ID =" . $stsk_src_id . " AND  STSK_FAC_CODE = " . $fac . " AND STSK_TYPE = 1)" );
  $stsk_src_id = 0;

}

$query  = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_ANCIENT_PRO, STSK_TYPE, STSK_LOCK, STSK_TICKET) VALUES ";

if($user_id == "Mi Departamento"){

$i = 0;

while( $fila = mysqli_fetch_row($team)){

   $query .= " ( " . $number . " , '" . $subject . "', '" . $descript . "', " . $fila[0] . ", '" . $fechaF . "', 2 ,  '" . $startD . "' , " . $muser . ", " . $fac . ", NULL, 0, 1, 1, '" . $ticket . "') ";
    $i = $i + 1;

   if( $i < mysqli_num_rows($team)  ){
    $query .= ",";
   }
$outcome .= $fila[0] . "|";


}

} elseif ($user_id == "Jefaturas") {

$i = 0;

  $team_leader = mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE (USR_FACILITY = " . $fac . " AND USR_RANGE = 'admin'); ");

     while($fila = mysqli_fetch_row($team_leader)){

            $query .= " ( " . ($number + $i + 1) . " , '" . $subject . "', '" . $descript . "', " . $fila[0] . ", '" . $fechaF . "', 2 ,  '" . $startD . "' , " . $muser . ", " . $fac . ", NULL, 0, 1, 1, '" . $ticket . "') ";
            
            $i = $i + 1;

           if( $i < mysqli_num_rows($team)){
                $query .= ",";
              }
              $outcome = $fila[0] . "|";
     }

} else {

$uq = explode("," , $user_id);
$earray = [];
$rarray = [];

for($i=0; $i < count($uq); $i++){
     $us = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_ID, USR_RANGE FROM USERS WHERE( CONCAT(USR_NAME, ' ', USR_SURNAME) = '" . $uq[$i] . "' AND USR_FACILITY = " . $fac . ")"));
     $earray[$i] = $us['USR_ID'];
     $rarray[$i] = $us['USR_RANGE'];
}

$query  = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_ANCIENT_PRO, STSK_TYPE, STSK_LOCK, STSK_TICKET, STSK_RESP)  VALUES";
   for($i=0; $i < count($earray); $i++){
      if($rarray[$i] == 'admin'){
       $query .= "  ( " . ($number + $i+1) . " , '" . $subject . "', '" . $descript . "', " . $earray[$i] . ", '" . $fechaF . "', 2 ,  '" . $startD . "' , " . $muser . ", " . $fac . ", NULL, 0, 1, 1, '" . $ticket . "', 2) ";
      } else {
       $query .= "  ( " . $number . " , '" . $subject . "', '" . $descript . "', " . $earray[$i] . ", '" . $fechaF . "', 2 ,  '" . $startD . "' , " . $muser . ", " . $fac . ", NULL, 0, 1, 1, '" . $ticket . "', 2) ";
      }
       
         //echo $query . "<br />";
           if( $i < count($earray)-1){
                $query .= ",";
              }
          $outcome .= $earray[$i] . "|";
   }



}


// cuando es el prime stsk  , no e
//echo $query . "<br />";
if(!mysqli_query($datos, $query)){

  echo mysqli_error($datos);

} else {
 

$uteam = mysqli_query($datos, "SELECT A.USR_ID, B.STSK_ID FROM USERS A INNER JOIN SUBTASKS B ON(A.USR_ID = B.STSK_CHARGE_USR AND B.STSK_ISS_ID = " . $number . " AND B.STSK_ISS_ID <> B.STSK_ID) WHERE (STSK_FAC_CODE =" . $fac . " AND STSK_TYPE= 1)");
 
    if($hdir = opendir("/var/www/html/" . $fac . "/_tmp/")) {

      while (false !== ($files = readdir($hdir))) {

     	  if(preg_match_all("/_\[" . $keyfile . "\]_/", $files) == 1){
          
     	 	  $extension = pathinfo($files, PATHINFO_EXTENSION);   

              while($uteams = mysqli_fetch_row($uteam)){

                if(copy("/var/www/html/" . $fac . "/_tmp/" . $files ,  $dir . $uteams[0] . "_alt/" . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_[" . $uteams[1] . "]_." . $extension)){
                        echo $dir . $uteams[0] . "_alt/" . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_[" . $uteams[1] . "]_." . $extension;
                  } else {
                      echo "/var/www/html/" . $fac . "/_tmp/" . $files;
                  }
              }
              
              mysqli_data_seek($uteam, 0);
        }
      
      }

    }
    
    closedir($hdir);

 echo (int)$number . "|" . $outcome . "|" . $ticket ;
}


?>

