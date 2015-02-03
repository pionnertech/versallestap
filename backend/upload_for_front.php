<?php

$fac = $_REQUEST['fac_id'];
$rut = $_REQUEST['rut'];

$dir = "/var/www/html/" . $fac;

if(!is_dir($dir . "reply/")){

	mkdir($dir . "reply/", 0775, true);
}



$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);


if(move_uploaded_file($_FILES['file']['tmp_name'], $dir . "reply/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_" . $rut . "_." . $extension  )) {
	echo $dir . "reply/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_" . $rut . "_." . $extension;
}

?>