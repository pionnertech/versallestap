<?php


$fac = $_GET['fac'];
$usr_id = $_GET['usr_id'];
$rut = $_GET['rut'];
$surname = $_GET['surmane'];
$importance = $_GET['imp'];
$msg = $_GET['msg'];
$dataF = date('Y-m-d h:i:s', strtotime(str_replace("/", "-", $_GET['dataF'])));
$dataS = $_GET['dataS'];
$iss_id = $_GET['iss_id'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

mysqli_query($datos, "UPDATE ISSUES SET ISS_CHARGE_USR = '" . $usr_id  . "' WHERE ISS_ID = " . $iss_id . ";");
mysqli_query($datos, "UPDATE ISSUES SET ISS_STATE = 2 WHERE ISS_ID = " . $iss_id   . ";");
mysqli_query($datos, "UPDATE ISSUES SET ISS_FINISH_DATE = '" . $dataF . "' WHERE ISS_ID = " . $iss_id   . ";");

$iss_ticket = mysql_fetch_assoc(mysqli_query($datos, "SELECT ISS_TICKET FROM ISSUES WHERE ISS_ID = " . $iss_id));


$query_insert = "INSERT INTO SUBTASKS(STSK_ISS_ID, STSK_DESCRIP, STSK_CHARGE_USR , STSK_STATE, STSK_FINISH_DATE , STSK_START_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_LOCK, STSK_TYPE, STSK_TICKET) ";
$query_insert .= "VALUES (" . $iss_id  . " , '" . $msg . "', '" . $usr_id  . "', 2, '" . $dataF . "','" . $dataS . "', '" . $usr_id  . "', " . $fac  . ", 0 , 0, 0, '" . $iss_ticket['ISS_TICKET'] . "')";


if(!mysqli_query($datos, $query_insert)){

echo "0";

} else {

echo "1";
}


$rut = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ISS_CTZ FROM ISSUES WHERE ISS_ID =" . $iss_id . ";" ));

$basedir = "/var/www/html/" . $fac . "/";
$targetdir = $basedir . "temporary/";

if(!is_dir($basedir . $usr_id . "/")){
	mkdir($basedir . $usr_id . "/", 0775, true);
}
//retive all file matching rut 
 if ($file_array = opendir("../" . $fac . "/temporary/" )){
       while (false !== ($archivos = readdir($file_array))){
           if(preg_match_all("/_(" . $rut['ISS_CTZ'] . ")_/", $archivos) == 1){
           	$extension = pathinfo($targetdir . $archivos,  PATHINFO_EXTENSION);
             if(copy($targetdir . $archivos , $basedir . $usr_id . "/" . basename(str_replace($rut['ISS_CTZ'], $iss_id , $archivos), "." . $extension ) . "_."  . $extension )) {
                 	echo "was upload_" . $archivos;
                    unlink($targetdir . $archivos);
                 } else {
                 	echo "wasnot upload" ;
                 }
           } else{
           	echo "no matching";
           }
          }
 } else {
 	echo "no opendir";
 }




?>