<?php


$file = $_FILES['img']['tmp_name'];
$user = $_POST['usr'];
$fac  = $_POST['entity'];


$dir  = "/var/www/html/" . $fac . "/img/";

if(!is_file($file)){
    echo "No es un archivo";
	exit;
}

$extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);

if(move_uploaded_file($file, $dir . $user . "." . $extension ) ){
   echo $dir . $user . $extension;
} else {
	echo "imposible de subir ... " . $dir . $user . "." . $extension;
}

?>