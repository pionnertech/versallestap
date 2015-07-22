<?php

$fac     = $_REQUEST['fac_id'];
$user    = $_REQUEST['user'];
$keyfile = $_REQUEST['keyfile'];

$iss_id = $keyfile;

if(!is_dir($dir)){
	mkdir($dir, 0775, true);
}
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");



switch ($user) {
	case 'Mi Departamento':
		//mysqli_query($datos, "SELECT ")
		break;
	case 'Jefaturas':
	 $users = [];
	 $pseudo	= mysqli_query($datos, "SELECT USR_ID FROM USERS   WHERE (USR_FACILITY =  " . $fac . " AND USR_RANGE = 'admin' )");
	 $i = 0;
     while($fila = mysqli_fetch_row($pseudo)){
     	$users[$i] = $fila[0]; 
     	$i = $i + 1;
     }
		break;
	default:
	 $users = [];
	 $pseudo  = explode(",", $user);
	 for($i=0; $i < count($pseudo); $i++){
	 	$p = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE CONCAT(USR_NAME, ' ', USR_SURNAME) = " . $pseudo[$i] ));
	 	$users[$i] = $p['USR_ID'];
	 }

		break;
}


	 for($i=0; $i < count($users); $i++){
	     $dir = "/var/www/html/" . $fac . "/" . $users[$i] . "/";
	 	$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	 	if(copy($_FILES['file']['tmp_name'] , $dir . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_." . $extension  )) {
	           echo $dir . basename($_FILES['file']['name'], "." . strtolower($extension)) . "_[" . $iss_id . "]_." . $extension ;
             }
	 	
	 }





?>