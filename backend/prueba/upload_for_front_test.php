<?php

$fac    = $_REQUEST['fac_id'];
$iss_id = $_REQUEST['iss_id'];
$user   = $_REQUEST['usr'];

$dir = "/var/www/html/" . $fac;


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$swt = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_OVER AS ID FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_FAC_CODE = " . $fac . ")"));



if($swt['ID'] == 1 ){

//get the sadmin when stsk over is true

$sadmin = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_ID AS ID  FROM USERS WHERE (USR_RANGE = 'sadmin' AND USR_FACILITY = " . $fac .")"));

$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

if(move_uploaded_file($_FILES['file']['tmp_name'], $dir . "/" . $sadmin['ID'] . "/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_" . $user . "." . $extension  )) {
	echo $dir . "/" . $sadmin['ID'] . "/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_." . $extension  ;
}


} else {


if(!is_dir($dir . "reply/")){
	mkdir($dir . "reply/", 0775, true);
}

$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

if(move_uploaded_file($_FILES['file']['tmp_name'], $dir . "/reply/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_." . $extension  )) {
	echo $dir . "reply/" . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_." . $extension  ;
}

}



?>