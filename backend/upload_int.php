<?php

$fac    = $_REQUEST['fac_id'];
$user   = $_REQUEST['user'];
$keyfile = $_REQUEST['keyfile'];

if(!isset($keyfile) || $keyfile == "" || is_null($keyfile)){
	$dir = "/var/www/html/" . $fac . "/" . $user . "_alt/";
	$iss_id = $_REQUEST['stsk'];

} else {
	$dir = "/var/www/html/" . $fac . "/_tmp/";
	$iss_id = $keyfile;
}

if(!is_dir($dir)){
	 mkdir($dir, 0775, true);
}

$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
if(move_uploaded_file($_FILES['file']['tmp_name'] , $dir . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_." . $extension  )) {
	echo $dir . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_." . $extension ;
}

?>