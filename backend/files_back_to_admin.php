<?php

$fac  = $_REQUEST['fac'];
$user = $_REQUEST['user'];
$stsk = $_REQUEST['stsk'];
$kind = $_REQUEST['kind'];

$datos = $datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


$query =  mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM SUBTASKS WHERE STSK_ID = " . $stsk ));

$userId = mysqli_query($datos, "SELECT STSK_CHARGE_USR FROM SUBTASKS WHERE STSK_ISS_ID = " . $query['STSK_ISS_ID']);

if($kind == 0 || $kind == "0"){
 while( $fila = mysqli_fetch_row($userId) ){
   $rdir = "/var/www/html/" . $fac . "/" . $fila[0] . "_in/";
    if(!is_dir($rdir)) {
        mkdir($rdir, 0775, true); 
     } 
   if($hdir = opendir($rdir)){
     while (false !== ($files = readdir($hdir))){
     	 if(preg_match_all("/_" . $stsk  . "_/", $files) == 1){
     	 	 $outcome .= "../". $fac . "/" . $fila[0] ."/" . $files . "|";;
     	}
    }
  }
}

} else {
 while( $fila = mysqli_fetch_row($userId) ){
   $rdir = "/var/www/html/" . $fac . "/" . $fila[0] . "_alt/";
       if(!is_dir($rdir)) {
        mkdir($rdir, 0775, true); 
     }
   if($hdir = opendir($rdir)){
     while (false !== ($files = readdir($hdir))){
     	 if(preg_match_all("/_\[" . $stsk  . "\]_/", $files) == 1){
     	 	 $outcome .= "../". $fac . "/" . $fila[0] ."/" . $files . "|";
     	 }
     }
   }
  }
}


echo $outcome;



?>