<?php 

$a = $_GET['usr_id'],
$iss_id = $_GET['iss_id'];
$fac = $_GET['fac'];


if($hdir = opendir("/var/www/html/" . $fac . "/" . $a . "/")){

     while (false !== ($files = readdir($hdir))){

     	 if(preg_match_all("/_" . $iss_id  . "_/", $files) == 1){
     	 	 $outcome .= $files . "|";
     	 }
     }
}
echo $outcome;

?>

