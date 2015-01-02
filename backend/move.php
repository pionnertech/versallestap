<?php

$iss_id = $_GET['iss_id'];
$usr_id = $_GET['usr_id'];
$fac = $_GET['fac'];


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

//retrive rut

$rut = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ISS_CTZ FROM ISSUES WHERE ISS_ID =" . $iss_id . ";" ));

$basedir = "/var/www/html/" . $fac . "/";
$targetdir = $basedir . "temporary/";

if(!is_dir($basedir . $usr_id . "/")){
	mkdir($basedir . $usr_id . "/", 0775, true);
}

//retive all file matching rut 
 if ($file_array = opendir("../" . $_SESSION['TxtFacility'] . "/temporary/" )){
       while (false !== ($archivos = readdir($file_array))){
           if(preg_match_all("/_" . $rut['ISS_CTZ'] . "_/", $archivos) == 1){
                 if(move_uploaded_file($archivos, $basedir . "/" . $usr_id . "/" . $archivos )){
                 	echo "was upload_" . $archivos;
                 } else {
                 	echo "no se pudo";
                 }
           }


          }
 }





?>