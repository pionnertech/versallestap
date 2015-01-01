<?php


echo $_GET['fac'];


$dir = "/var/www/html/10000/";

if(!is_dir($dir . "temporary/")){

	mkdir($dir . "temporary/", 0775, true);
}


if(move_uploaded_file($_FILES['file']['tmp-name']), $dir . "temporary/" . $_FILES['file']['name'])){
	echo "was uploaded";
}




?>