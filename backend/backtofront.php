<?php

$p    = $_GET['file'];
$usr  = $_GET['usr'];
$fac  = $_GET['fac'];
$iss  = $_GET['iss'];

$dir = "/var/www/html/" . $fac . "/" . $usr . "/";

$file = preg_replace('/[0-9]+/' , $iss , $p);


if(copy($dir . $p, "/var/www/html/" . $fac . "/reply/" . $file)){
	echo "copiado!";
} else {
	echo "no copiado"
}





?>