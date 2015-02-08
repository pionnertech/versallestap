<?php

$fac = $_REQUEST['fac_id'];
$rut = $_REQUEST['rut'];

$dir = "/var/www/html/" . $fac;

if(!is_dir($dir . "/temporary/")){

	mkdir($dir . "/temporary/", 0775, true);
}

$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);


if(move_uploaded_file($_FILES['file']['tmp_name'], $dir . "/temporary/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_" . $rut . "_." . $extension  )) {
	echo $dir . "/temporary/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_" . $rut . "_." . $extension;
}




?>