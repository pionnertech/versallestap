<?php

$fac  = $_REQUEST['fac'];
$user = $_REQUEST['user'];
$stsk = $_REQUEST['stsk'];
$kind = $_REQUEST['kind'];

if($kind == 0 || $kind == "0"){
   $rdir = "/var/www/html/" . $fac . "/" . $user . "_in/";
   if($hdir = opendir($rdir)){

     while (false !== ($files = readdir($hdir))){

     	 if(preg_match_all("/_" . $stsk  . "_/", $files) == 1){
     	 	 $outcome .= $files . "|";
     	 }
     }
}
} else {
	
   $rdir = "/var/www/html/" . $fac . "/" . $user . "_alt/";
   if($hdir = opendir($rdir)){

     while (false !== ($files = readdir($hdir))){

     	 if(preg_match_all("/_\[" . $stsk  . "\]_/", $files) == 1){
     	 	 $outcome .= $files . "|";
     	 }
     }
}
}


echo $outcome;



?>