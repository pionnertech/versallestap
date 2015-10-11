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

if(substr($post_trigger['PSD_TICKET'], 0, 2) == 'EX'){


$query = mysqli_query($datos, "SELECT A.ISS_PROGRESS,  A.ISS_ID, A.STSK_TYPE, B.USR_RANGE, A.ISS_TICKET  FROM SUBTASKS  A INNER JOIN USERS B ON (B.USR_ID = A.STSK_CHARGE_USR) WHERE (USR_RANGE = 'sadmin' AND ISS_FAC_CODE = " . $fac . " AND ISS_CHARGE_USR = "  . $usr . " AND ISS_TICKET = '" . $post_trigger['PSD_TICKET'] ."')");
$query_assoc = mysqli_fetch_assoc($query);

//what is the progress
 $add = mysqli_fetch_assoc(mysqli_query($datos, "SELECT  SUM(A.STSK_PROGRESS), COUNT(A.STSK_ID), ROUND(AVG(IFNULL(A.STSK_PROGRESS, 0))) AS PROGRESS FROm SUBTASKS A INNER JOIN USERS B ON(B.USR_RANGE ='admin' AND A.STSK_CHARGE_USR = B.USR_ID) WHERE (STSK_FAC_CODE = " . $fac . " AND STSK_TICKET = '" . $query_assoc['ISS_TICKET'] . "')")); 
 $handler = mysqli_fetch_assoc(mysqli_query($datos, "SELECT PSD_ID, PSD_USR, PSD_PERCENT FROM PSEUDO WHERE (PSD_FAC_CODE = " . $fac . " AND PSD_TICKET = '" . $query_assoc['ISS_TICKET'] . "') ORDER BY PSD_TIMESTAMP DESC" ));
 $trfm = mysqli_fetch_assoc(mysqli_query($datos, "SELECT CONCAT(USR_NAME, ' ' , USR_SURNAME) AS NAME FROM USERS WHERE USR_ID = " . $handler['PSD_USR'] . ";"));


$user_out1 = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($trfm['NAME'])))); //Nombre del usuario admin
$user_out2 = $handler['PSD_USR']; // id del user
$user_out3 = $handler['PSD_ID']; //id del subtasks
$user_out4 = $handler['PSD_PERCENT']; //percent
$user_out5 = $add['PROGRESS'];// 
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



} else {

$query = mysqli_query($datos, "SELECT A.STSK_PROGRESS, A.STSK_ANCIENT_PRO, A.STSK_ID, A.STSK_ISS_ID, A.STSK_TYPE, B.USR_RANGE, A.STSK_TICKET  FROM SUBTASKS  A INNER JOIN USERS B ON (B.USR_ID = A.STSK_CHARGE_USR) WHERE (USR_RANGE = 'sadmin' AND STSK_TYPE = 0 AND STSK_FAC_CODE = " . $fac . " AND STSK_MAIN_USR = "  . $usr . " AND STSK_TICKET = '" . $post_trigger['PSD_TICKET'] ."')");
$query_assoc = mysqli_fetch_assoc($query);

//what is the progress?

$add = mysqli_fetch_assoc(mysqli_query($datos, "SELECT  SUM(A.STSK_PROGRESS), COUNT(A.STSK_ID), ROUND(AVG(IFNULL(A.STSK_PROGRESS, 0))) AS PROGRESS FROm SUBTASKS A INNER JOIN USERS B ON(B.USR_RANGE ='admin' AND A.STSK_CHARGE_USR = B.USR_ID) WHERE (STSK_FAC_CODE = " . $fac . " AND STSK_TICKET = '" . $query_assoc['STSK_TICKET'] . "')")); 

//who is?, which percentage?,
 
$handler = mysqli_fetch_assoc(mysqli_query($datos, "SELECT PSD_ID, PSD_USR, PSD_PERCENT FROM PSEUDO WHERE (PSD_FAC_CODE = " . $fac . " AND PSD_TICKET = '" . $query_assoc['STSK_TICKET'] . "') ORDER BY PSD_TIMESTAMP DESC" ));

//transform all into readable info
$trfm = mysqli_fetch_assoc(mysqli_query($datos, "SELECT CONCAT(USR_NAME, ' ' , USR_SURNAME) AS NAME FROM USERS WHERE USR_ID = " . $handler['PSD_USR'] . ";"));


$user_out1 = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($trfm['NAME'])))); //Nombre del usuario admin
$user_out2 = $handler['PSD_USR']; // id del user
$user_out3 = $handler['PSD_ID']; //
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

}










?>