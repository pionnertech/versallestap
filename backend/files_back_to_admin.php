<?php

$fac     = $_REQUEST['fac'];
$user    = $_REQUEST['user'];
$stsk    = $_REQUEST['stsk'];
$kind    = $_REQUEST['kind'];
$current = $_REQUEST['current'];
$first   = $_REQUEST['first'];
$bingo   = false;

if($first == 1){
  exit;
}

if($current !== $user){
  $bingo = true;
}

$datos = $datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query =  mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM SUBTASKS WHERE STSK_ID = " . $stsk ));

$userId = mysqli_query($datos, "SELECT STSK_CHARGE_USR, STSK_ID FROM SUBTASKS WHERE (STSK_ISS_ID = " . $query['STSK_ISS_ID'] . " AND STSK_FAC_CODE = " . $fac . ");");

if($kind == 0 || $kind == "0"){
 while( $fila = mysqli_fetch_row($userId) ){
   $rdir = "/var/www/html/" . $fac . "/" . $fila[0] . "_in/";
    if(!is_dir($rdir)) {
        mkdir($rdir, 0775, true); 
     } 
   if($hdir = opendir($rdir)){
     while (false !== ($files = readdir($hdir))){
     	 if(preg_match_all("/_" . $stsk  . "_/", $files) == 1){
         if($bingo == true){
            $outcome .= "../". $fac . "/" . $fila[0] ."_in/" . $files . "|";
          } else {
              if($user == $fila[0] ){
                $outcome .= "../". $fac . "/" . $fila[0] ."_in/" . $files . "|";
            } else {
              continue;
          }
        }
     	}
    }
  }
  closedir($hdir);
}
} else {

 while( $fila = mysqli_fetch_row($userId) ){
   $rdir = "/var/www/html/" . $fac . "/" . $fila[0] . "_alt/";
       if(!is_dir($rdir)) {
            mkdir($rdir, 0775, true); 
        }
   if($hdir = opendir($rdir)){
     while (false !== ($files = readdir($hdir))){
     	 if(preg_match_all("/_\[" . $fila[1]  . "\]_/", $files) == 1){
         if($bingo == true){
            $outcome .= "../". $fac . "/" . $fila[0] ."_alt/" . $files . "|";
          } else {
              if((int)$user == (int)$fila[0] ){
                $outcome .= "../". $fac . "/" . $fila[0] ."_alt/" . $files . "|";
            } else {
              continue;
          }
        }
     	 }
     }
   }
   closedir($hdir);
  }
}


echo $outcome;



?>