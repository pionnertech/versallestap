<?php

$fac  = $_REQUEST['fac'];
$user = $_REQUEST['user'];
$stsk = $_REQUEST['stsk'];

if($hdir = opendir("/var/www/html/" . $fac . "/" . $user . "_in/")){

     while (false !== ($files = readdir($hdir))){

     	 if(preg_match_all("/_" . $stsk  . "_/", $files) == 1){
     	 	 $outcome .= $files . "|";
     	 }
     }
}
echo $outcome;



?>