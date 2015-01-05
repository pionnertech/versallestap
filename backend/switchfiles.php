<?php


$fac = $_GET['fac'];
$file_name = $_GET['file_name'];
$main_usr_id = $_GET['main_usr_id'];
$charge_usr_id = $_GET['charge_usr_id'];

//first check if exist
$dir = "/var/www/html/" . $fac . "/";
if(!is_file($dir . $main_usr_id . "/" . $file_name)){
	echo "no file!";
	exit;
}

 if(copy($dir . $main_usr_id . "/" . $file_name, $dir . $charge_usr_id . "/" . $file_name )){
 	echo "was copied";
 	unlink($dir . $main_usr_id . "/" . $file_name);
 } else {
 	echo $dir . $main_usr_id . "/" . $file_name  . "<br />";
 	echo $dir . $main_usr_id . "/" . $file_name
 }
?>