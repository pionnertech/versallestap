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

// modificado para recibir internos variables $dir = "/var/www/html/" . $fac . "/" . $user . "_alt/";


$outcome = $keyfile . "|" . $stsk_src_id . "|";

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


//stsk_src_id == 0 means no parent task

//seek the range of both

$dept = mysqli_fetch_assoc(mysqli_query($datos,"SELECT USR_DEPT FROM USERS WHERE (USR_ID = " . $muser . " AND USR_FACILITY= " . $fac . ")"));

$team = mysqli_query($datos, "SELECT USR_ID WHERE (USR_DEPT = " . $dept['USR_DEPT'] . " AND USR_FACILITY = " . $fac . ")");

$inquiry = mysqli_query($datos, "SELECT USR_RANGE FROM USERS WHERE USR_ID =" . $user);
while($val = mysqli_fetch_row($inquiry)){
   $user_range  = $val[0] ;
}



if($stsk_src_id == 0){

$query_es  = "INSERT INTO SUBTASKS (STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_ANCIENT_PRO, STSK_TYPE, STSK_LOCK) ";
$query_es .= "VALUES ('" . $subject . "', '" . $descript . "', '" . $muser . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", 0, 0,  1, 0)";

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

   $query .= " ( " . $number . " , '" . $subject . "', '" . $descript . "', '" . $fila[0] . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", NULL, 0, 1, 0) ";
    $i = $i + 1;

   if( $i < mysqli_num_rows($team)  ){
    $query .= ",";
   }

}

} elseif ($user_id == "Jefaturas") {

$i = 0;

  $team_leader = mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE (USR_FACILITY = " . $fac . " AND USR_RANGE = 'admin'); ");

     while($fila = mysqli_fetch_row($team_leader)){

            $query .= " ( " . $number . " , '" . $subject . "', '" . $descript . "', '" . $fila[0] . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", NULL, 0, 1, 0) ";
            
            $i = $i + 1;

           if( $i < mysqli_num_rows($team)){
                $query .= ",";
              }
     }
   
} else {

$users = explode("," , $user_id);
$query  = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_ANCIENT_PRO, STSK_TYPE, STSK_LOCK)  VALUES";
   for ($i=0; $i < count($users); $i++){
        $query .= "  ( " . $number . " , '" . $subject . "', '" . $descript . "', '" . $users[$i] . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", NULL, 0, 1, 0) ";
         $i = $i +1;
           if( $i < count($users)){
                $query .= ",";
              }
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
      $query .= "VALUES ( " . $stsk_src_id . ", '" . $subject . "', '" . $descript . "', '" . $users[$i] . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", NULL, 0, 1, 0);";
    } else{

      $query  = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_TYPE, STSK_LOCK) ";
      $query .= "VALUES ( " . $stsk_src_id . ", '" . $subject . "', '" . $descript . "', '" . $users[$i] . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", 0, 1, 0)";

    }

}


}


// cuando es el prime stsk  , no ed




if(!mysqli_query($datos, $query)){

  echo mysqli_error($datos);

} else {

//esto va para todos

  //si es para el primero

  if($stsk_src_id == 0) {
         $variable = mysqli_query($datos , "UPDATE SUBTASKS SET STSK_ISS_ID = " . $number . " WHERE STSK_ID = " . ((int)$number + 1));
         $pre_id   = mysqli_fetch_assoc(mysqli_query($datos, "SELECT LAST_INSERT_ID(STSK_ID) AS IND FROM SUBTASKS order BY STSK_ID DESC limit 1"));
         $stsk_id  = $pre_id['IND'];
  }  else {
 
         $pre_id   = mysqli_fetch_assoc(mysqli_query($datos, "SELECT LAST_INSERT_ID(STSK_ID) AS IND FROM SUBTASKS order BY STSK_ID DESC limit 1"));
         $stsk_id  = $pre_id['IND'];
  }

     //======
  //enviar el nombre hacia el cliente
  $name  = mysqli_fetch_assoc(mysqli_query($datos, "SELECT CONCAT(USR_NAME, ' ' , USR_SURNAME) AS NAME FROM USERS WHERE USR_ID = " . $user));
  

    if($hdir = opendir("/var/www/html/" . $fac . "/_tmp/")) {

      while (false !== ($files = readdir($hdir))) {
        
     	  if(preg_match_all("/_\[" . $keyfile . "\]_/", $files) == 1){

     	 	  $extension = pathinfo($files, PATHINFO_EXTENSION);   

            $outcome .= $dir . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_[" . $stsk_id . "]_." . $extension . "|";

              if(copy("/var/www/html/" . $fac . "/_tmp/" . $files ,  $dir . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_[" . $stsk_id . "]_." . $extension)){

     	 	   	    unlink("/var/www/html/" . $fac . "/_tmp/" . $files);
     	    }
        }
      }
    }
    closedir($hdir);

 echo (int)$stsk_id -1 . "|" . $name['NAME'] . "|" . $outcome . "|" ;

}


?>

