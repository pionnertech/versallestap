<?php
$muser       = $_GET['muser'];
$user        = $_GET['user'];
$fechaF      = date('Y-m-d h:i:s', strtotime(str_replace("/","-",$_GET['fechaF'])));
$stsk_id     = $_GET['stsk'];
$subject     = $_GET['subject'];
$descript    = $_GET['descript'];
$startD      = $_GET['startD'];
$fac         = $_GET['fac'];
$stsk_src_id = $_GET['main_stsk'];
$keyfile     = $_GET['keyfile'];
$number      = 0;
$muser_range = "";

$outcome = "";
 $dir = "/var/www/html/" . $fac . "/";


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$dept = mysqli_fetch_assoc(mysqli_query($datos,"SELECT USR_DEPT FROM USERS WHERE (USR_ID = " . $muser . " AND USR_FACILITY= " . $fac . ")"));

$team = mysqli_query($datos, "SELECT USR_ID WHERE (USR_DEPT = " . $dept['USR_DEPT'] . " AND USR_FACILITY = " . $fac . ")");


if($stsk_src_id == 0){

$query_es  = "INSERT INTO SUBTASKS (STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_ANCIENT_PRO, STSK_TYPE, STSK_LOCK) ";
$query_es .= "VALUES ('" . $subject . "', '" . $descript . "', '" . $muser . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", 0, 0,  1, 1)";

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

}





if ($stsk_src_id == 0){

$query  = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_ANCIENT_PRO, STSK_TYPE, STSK_LOCK) VALUES ";

if($user_id == "Mi Departamento"){

$i = 0;

while( $fila = mysqli_fetch_row($team)){

   $query .= " ( " . $number . " , '" . $subject . "', '" . $descript . "', '" . $fila[0] . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", NULL, 0, 1, 1) ";
    $i = $i + 1;

   if( $i < mysqli_num_rows($team)  ){
    $query .= ",";
   }
$outcome = $fila[0] . "|";

}

} elseif ($user_id == "Jefaturas") {

$i = 0;

  $team_leader = mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE (USR_FACILITY = " . $fac . " AND USR_RANGE = 'admin'); ");

     while($fila = mysqli_fetch_row($team_leader)){

            $query .= " ( " . $number . " , '" . $subject . "', '" . $descript . "', '" . $fila[0] . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", NULL, 0, 1, 1) ";
            
            $i = $i + 1;

           if( $i < mysqli_num_rows($team)){
                $query .= ",";
              }
              $outcome = $fila[0] . "|";
     }
   
} else {

$uq = explode("," , $user);
$earray = [];

for($i=0; $i < count($uq); $i++){
     $us = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE( CONCAT(USR_NAME, ' ', USR_SURNAME) = '" . $uq[$i] . "' AND USR_FACILITY = " . $fac . ")"));
     $earray[$i] = $us['USR_ID'];
}

$query  = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_ANCIENT_PRO, STSK_TYPE, STSK_LOCK)  VALUES";
   for($i=0; $i < count($earray); $i++){
        $query .= "  ( " . $number . " , '" . $subject . "', '" . $descript . "', " . $earray[$i] . ", '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", NULL, 0, 1, 1) ";
         
           if( $i < count($earray)-1){
                $query .= ",";
              }
          $outcome .= $earray[$i] . "|";
   }

}

} else {

$query = "";

$users = explode("," , $user_id);

for ($i=0; $i < count($users);$i++){
//get the rage of user
 $user_range = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_RANGE WHERE( CONCAT(USR_NAME, ' ', USR_SURNAME) = " . $user[0] . " AND USR_FACILITY = " . $fac . ")"));

    if($user_ramge['USR_RANGE'] == 'admin'){
      $query .= "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_ANCIENT_PRO ,STSK_TYPE, STSK_LOCK) ";
      $query .= "VALUES ( " . $stsk_src_id . ", '" . $subject . "', '" . $descript . "', '" . $users[$i] . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", NULL, 0, 1, 1);";
    } else{

      $query  = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_TYPE, STSK_LOCK) ";
      $query .= "VALUES ( " . $stsk_src_id . ", '" . $subject . "', '" . $descript . "', '" . $users[$i] . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", 0, 1, 1)";

    }

}


}

// cuando es el prime stsk  , no ed

if(!mysqli_query($datos, $query)){

  echo mysqli_error($datos);

} else {

if( $stsk_src_id !== 0 || $stsk_src_id !== "" ){
     $keyfile = $stsk_src_id;
  } 

$uteam = mysqli_query($datos, "SELECT A.USR_ID, B.STSK_ID FROM USERS INNER JOIN SUBTASKS ON(A.USR_ID = B.STSK_CHARGE_USR AND B.STSK_ISS_ID = " . $number . ") WHERE (STSK_FAC_CODE =" . $fac . " AND STSK_TYPE= 1)");
 
    if($hdir = opendir("/var/www/html/" . $fac . "/_tmp/")) {

      while (false !== ($files = readdir($hdir))) {
        
     	  if(preg_match_all("/_\[" . $keyfile . "\]_/", $files) == 1){

     	 	  $extension = pathinfo($files, PATHINFO_EXTENSION);   

              while($uteams = mysqli_fetch_row($uteam)){
                    
                if(copy("/var/www/html/" . $fac . "/_tmp/" . $files ,  $dir . $uteams[0] . "_alt/" . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_[" . $uteams[1] . "]_." . $extension)){
                        echo $dir . $uteams[0] . "_alt/" . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_[" . $uteams[1] . "]_." . $extension;
                   unlink("/var/www/html/" . $fac . "/_tmp/" . $files);

                  }

              }
              mysqli_data_seek($uteam, 0);
        }
      }
    }
    closedir($hdir);

 echo (int)$number . "|" . $outcome . "|" ;

}


?>

