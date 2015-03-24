<?php

$fac = $_REQUEST['fac'];
$iss_id = $_REQUEST['stsk'];

$dir = "/var/www/html/" . $fac;

if(!is_dir($dir . "/" . $user . "_alt/")){
	 mkdir($dir . "/" . $user . "_alt/", 0775, true);
}

$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
if(move_uploaded_file($_FILES['file']['tmp_name'] , $dir . "/" . $user . "_alt/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_." . $extension  )) {
	echo $dir . "/" . $user . "_alt/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_." . $extension  ;
}

?>