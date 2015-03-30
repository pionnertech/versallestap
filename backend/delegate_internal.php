<?php

$muser       = $_GET['muser'];
$user        = $_GET['user'];
$fechaF      = date('Y-m-d h:i:s', strtotime(substr($_GET['fechaF'], 0, 10)));
$stsk_id     = $_GET['stsk'];
$subject     = $_GET['subject'];
$descript    = $_GET['descript'];
$startD      = $_GET['startD'];
$fac         = $_GET['fac'];
$stsk_src_id = $_GET['main_stsk'];
$keyfile     = $_GET['keyfile'];


$dir = "/var/www/html/" . $fac . "/" . $user . "_alt/";
$outcome = $keyfile . "|";

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


if (isset($stsk_src_id)){

$query = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_TYPE) ";
$query .= "VALUES ( " . $stsk_src_id . " , '" . $subject . "', '" . $descript . "', '" . $user . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", 0, 1)";
} else {

$query_es  = "INSERT INTO SUBTASKS (STSK_SUBJECT, STSK_DESCRIP ,STSK_CHARGE_USR, STSK_FINISH_DATE, STSK_STATE, STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_TYPE) ";
$query_es .= "VALUES ('" . $subject . "', '" . $descript . "', '" . $muser . "', '" . $fechaF . "', 2 ,  '" . $startD . "' , '" . $muser . "', " . $fac . ", 0, 1)";
}

// cuando es el prime stsk 
if(!isset($stsk_src_id)){
  $hds = mysqli_query($datos, $query_es);
  $number = mysqli_insert_id($hds);
  if(mysqli_query($datos , "UPDATE SUBTASKS SET STSK_ISS_ID = " . $number . " WHERE STSK_ID = " . $number ."")){
        $outcome .= "yes|";
  } else {
      echo mysqli_error($datos);
  }
}


if(!mysqli_query($datos, $query)){

echo mysqli_error($datos);

} else {


  $name  = mysqli_fetch_assoc(mysqli_query($datos, "SELECT CONCAT(USR_NAME, ' ' , USR_SURNAME) AS NAME FROM USERS WHERE USR_ID = " . $user));
  
  if(isset($keyfile) || $keyfile !== "" || !is_null($keyfile)){

  	$stsk_id = mysqli_insert_id($datos);

      if($hdir = opendir("/var/www/html/" . $fac . "/_tmp/")){

        while (false !== ($files = readdir($hdir))){
        
     	 if(preg_match_all("/_\[" . $keyfile . "\]_/", $files) == 1){
     	 	 $extension = pathinfo($files, PATHINFO_EXTENSION); 
     	 	// $outcome .=  $dir . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_[" . $stsk_id . "]_." . $extension . "|";   
    if(copy("/var/www/html/" . $fac . "/_tmp/" . $files ,  $dir . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_[" . $stsk_id . "]_." . $extension)){
     	 	   	    unlink("/var/www/html/" . $fac . "/_tmp/" . $files);
     	 }
     }
  }
  
}

closedir($hdir);
}

 echo mysqli_insert_id($datos) . "|" . $name['NAME'] . "|" . $outcome;



}


?>