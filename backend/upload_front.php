<?php

$fac = $_REQUEST['fac_id'];
$rut = $_REQUEST['rut'];
$name = $_REQUEST['name'];
,l
$dir = "/var/www/html/" . $fac;

if(!is_dir($dir . "/temporary/")){
	mkdir($dir . "/temporary/", 0775, true);
}

$extension = pathinfo($name, PATHINFO_EXTENSION);


if(move_uploaded_file($_FILES['file']['tmp_name'], $dir . "/temporary/" . basename($name, "." . strtolower($extension)) . "_" . $rut . "_." . $extension  )) {
	echo $dir . "/temporary/" . basename($name, "." . strtolower($extension)) . "_" . $rut . "_." . $extension;
}




?>