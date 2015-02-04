<?php

$fac = $_REQUEST['fac_id'];
$iss_id = $_REQUEST['iss_id'];

$dir = "/var/www/html/" . $fac;

if(!is_dir($dir . "reply/")){
	mkdir($dir . "reply/", 0775, true);
}

$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

if(move_uploaded_file($_FILES['file']['tmp_name'], $dir . "/reply/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_." . $extension  )) {
	echo $dir . "reply/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_." . $extension  ;
}

?>