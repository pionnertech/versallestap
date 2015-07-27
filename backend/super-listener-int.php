<?php 

$fac = $_GET['fac'];
$usr = $_GET['usr'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$trigger = mysqli_query($datos, "SELECT PSD_ID, PSD_TICKET FROM PSEUDO WHERE PSD_FAC_CODE = " . $fac);

if(mysqli_num_rows($trigger) == 0){

$user_out1 = 0;
$user_out2 = 0;
$user_out3 = 0;
$user_out4 = 0;
$user_out5 = 0;

echo "|" . $user_out1;
echo "|" . $user_out2;
echo "|" . $user_out3;
echo "|" . $user_out4;
echo "|" . $user_out5;
exit;

}
$post_trigger = mysqli_fetch_assoc($trigger);
//output the general and individual progress
$query = mysqli_query($datos, "SELECT A.STSK_PROGRESS, A.STSK_ANCIENT_PRO, A.STSK_ID, A.STSK_ISS_ID, A.STSK_TYPE, B.USR_RANGE, A.STSK_TICKET  FROM SUBTASKS  A INNER JOIN USERS B ON (B.USR_ID = A.STSK_CHARGE_USR) WHERE (USR_RANGE = 'sadmin' AND STSK_TYPE = 1 AND STSK_FAC_CODE = " . $fac . " AND STSK_MAIN_USR = "  . $usr . " AND STSK_TICKET = '" . $post_trigger['PSD_TICKET'] ."')");
$query_assoc = mysqli_fetch_assoc($query);

//what is the progress?

  $add = mysqli_fetch_assoc(mysqli_query($datos, "SELECT  SUM(A.STSK_PROGRESS), COUNT(A.STSK_ID), ROUND(AVG(A.STSK_PROGRESS)) AS PROGRESS FROm SUBTASKS A INNER JOIN USERS B ON(B.USR_RANGE ='admin' AND A.STSK_CHARGE_USR = B.USR_ID) WHERE (STSK_FAC_CODE = " . $fac . " AND STSK_TICKET = '" . $query_assoc['STSK_TICKET'] . "')")); 

//who is?, which percentage?,
 
$handler = mysqli_fetch_assoc(mysqli_query($datos, "SELECT PSD_ID, PSD_USR, PSD_PERCENT FROM PSEUDO WHERE (PSD_FAC_CODE = " . $fac . " AND PSD_TICKET = '" . $query_assoc['STSK_TICKET'] . "') ORDER BY PSD_TIMESTAMP DESC" ));

//transform all into readable info
$trfm = mysqli_fetch_assoc(mysqli_query($datos, "SELECT CONCAT(USR_NAME, ' ' , USR_SURNAME) AS NAME FROM USERS WHERE USR_ID = " . $handler['PSD_USR'] . ";"));


$user_out1 = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($trfm['NAME']))));
$user_out2 = $handler['PSD_USR'];
$user_out3 = $handler['PSD_ID'];
$user_out4 = $handler['PSD_PERCENT'];
$user_out5 = $add['PROGRESS'];
$user_out6 = $post_trigger['PSD_TICKET'];

if(!mysqli_query($datos, "DELETE FROM PSEUDO WHERE PSD_ID = " . $handler['PSD_ID']  )){
	  mysqli_error($datos);

} else {

echo "|" . $user_out1;
echo "|" . $user_out2;
echo "|" . $user_out3;
echo "|" . $user_out4;
echo "|" . $user_out5;
echo "|" . $user_out6 . "|";

}

//iterate every dir for every file by quering by iss
$it = mysqli_query($datos, "SELECT A.STSK_ID FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID ) WHERE (STSK_TYPE = 1 AND STSK_CHARGE_USR <> STSK_MAIN_USR AND STSK_TICKET= '" . $query_assoc['STSK_TICKET'] . "' AND STSK_FAC_CODE = " . $fac . " AND USR_RANGE = 'back-user')");

$handle = opendir("/var/www/html/" . $fac . "/" . $handler['PSD_USR'] . "_alt/");

while($fila = mysqli_fetch_row($it)){
    while (false !== ($file = readdir($handle))){
    	echo $file . "  - -- " . preg_match_all("/_\[" . $fila[0] . "\]_/", $file) . " --- " . "preg_match_all(/_\[" . $fila[0] . "\]_/," . $file . ")";
	   if( preg_match_all("/_\[" . $fila[0] . "\]_/", $file) == 1){
             echo $file . ",";
	    }
    }
}



?>