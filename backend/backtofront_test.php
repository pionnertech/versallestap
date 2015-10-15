<?php

$p    = $_GET['file'];
$usr  = $_GET['usr'];
$fac  = $_GET['fac'];
$iss  = $_GET['iss'];

$dir = "/var/www/html/" . $fac . "/" . $usr . "_in/";
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


$swt = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_OVER AS SW FROM SUBTASKS WHERE (STSK_FAC_CODE = " . $fac . " AND STSK_ISS_ID =" . $iss . ") "));

if($swt['SW'] == 1){


$sadmin = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE (USR_FACILITY = " . $fac . " AND USR_RANGE = 'sadmin')"));

$file_pre = preg_replace('/_[0-9]+_/' ,  '[' . $iss . ']' , $p);
$file     = preg_replace('/(\d+)(?!.*\d)/', $usr, $file_pre);

if(copy($dir . $p, "/var/www/html/" . $fac . "/" . $sadmin['USR_ID'] . "/" . $file)){
	echo "/var/www/html/" . $fac . "/" . $sadmin['USR_ID'] . "/" . $file;
} else {
	echo "no copiado";
}

} else {



$file = preg_replace('/[0-9]+/' , "[" . $iss . "]" , $p);

if(copy($dir . $p, "/var/www/html/" . $fac . "/reply/" . $file)){
	echo "/var/www/html/" . $fac . "/reply/" . $file;
} else {
	echo "no copiado";
}


}








?>