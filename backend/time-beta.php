<?php

//time nuevo para la escucha de progresos

$usr = $_GET['usr'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
//who is listening?
$ident = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_RANGE AS RAN FROM USERS WHERE USR_ID = " . $usr));
//aqui se detectan los diferenciales de progreso independiente de que origan sean 
$first = mysqli_query($datos, "SELECT A.STSK_PROGRESS, A.STSK_ANCIENT_PRO, A.STSK_ID, A.STSK_ISS_ID, A.STSK_TYPE, B.USR_RANGE, A.STSK_TICKET, CONCAT(B.USR_NAME, ' ', B.USR_SURNAME) AS NAME FROM SUBTASKS  A INNER JOIN USERS B ON (B.USR_ID = A.STSK_CHARGE_USR) WHERE (STSK_FAC_CODE = " . $fac . " AND STSK_MAIN_USR = " . $usr . " AND STSK_PROGRESS <> STSK_ANCIENT_PRO ) LIMIT 1 DESC");
//si no encuentra, salga
if(mysqli_num_rows($first) < 1){
     exit; 
} 

//si encuentra, veamos de que se trata., de quien para quien...

//dicriminemos origen y tipo

$sel = mysqli_fetch_assoc($first);

switch ($sel['STSK_TYPE']) {
	case 0:
	   
	   //se saca el promedio y se obtien el valor parcial  
       //  =======  caso back =======//


	  // determine if is Finished
	   if($sel['STSK_PROGRESS'] > 99.9){  
	      	$classText = "FINALIZADO"; } else {  
	     	$classText = "" ;
	     }
	   

	   //update the difference , set the traffic and variables
       mysqli_query($datos, "UPDATE SUBTAKS SET STSK_ANCIENT_PRO = STSK_PROGRESS WHERE STSK_ID = " . $sel["STSK_ID"]);
       //get the gral avg
	   $total = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(CASE STSK_PROGRESS IF IS NULL THEN 0 ELSE STSK_PROGRESS END)) AS AVX FROM SUBTASKS WHERE (STSK_TICKET = '" . $sel['STSK_TICKET'] . "' AND STSK_FAC_CODE = " . $fac . " AND STSK_MAIN_USR =  " . $usr . ")" ));
       //get the INFO about the partial progress
       $trf_str = mysqli_fetch_assoc(mysqli_query($datos, "SELECT * FROM TRAFFIC WHERE TRF_STSK_ID = " . $sel['STSK_ID']));
       
                echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($sel['NAME']))));
                echo "|" . $trf_str['TRF_USER'];
                echo "|" . $trf_str['TRF_SUBJECT'];
                echo "|" . $trf_str['TRF_DESCRIPT'];
                echo "|" . date('d/m/Y', strtotime( $trf_str['TRF_ING_DATE']));
                echo "|" . $trf_str['TRF_STSK_SRC_ID'];
                echo "|" . $total['AVX'];
                echo "|" . $classText;
                echo "|" . $trf_str['TRF_STSK_ID']; 
                echo "|" . $sel['STSK_PROGRESS'];
                echo "|" . $sel['STSK_TYPE'];


		break;
	
	case 1:
	   //se saca el promedio y se obtien el valor parcial  
       //  =======  caso back =======//
       if($sel['STSK_PROGRESS'] > 99.9){  
           	$classText = "FINALIZADO";  } else { 
            $classText = "" ;
        }
	   //update the difference , set the traffic and variables
       mysqli_query($datos, "UPDATE SUBTAKS SET STSK_ANCIENT_PRO = STSK_PROGRESS WHERE STSK_ID = " . $sel["STSK_ID"]);
       //get the gral avg
	   $total = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(CASE STSK_PROGRESS IF IS NULL THEN 0 ELSE STSK_PROGRESS END)) AS AVX FROM SUBTASKS WHERE (STSK_TICKET = '" . $sel['STSK_TICKET'] . "' AND STSK_FAC_CODE = " . $fac . " AND STSK_MAIN_USR =  " . $usr . ")" ));
       //get the INFO about the partial progress
       $trf_str = mysqli_fetch_assoc(mysqli_query($datos, "SELECT * FROM TRAFFIC_II WHERE TII_STSK_ID = " . $sel['STSK_ID']));
       
                echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($sel['NAME']))));
                echo "|" . $trf_str['TRF_USER'];
                echo "|" . $trf_str['TRF_SUBJECT'];
                echo "|" . $trf_str['TRF_DESCRIPT'];
                echo "|" . date('d/m/Y', strtotime($trf_str['TRF_ING_DATE']));
                echo "|" . $user['TRF_STSK_SRC_ID'];
                echo "|" . $total['AVX'];
                echo "|" . $classText;
                echo "|" . $trf_str['TII_STSK_ID']; 
                echo "|" . $sel['STSK_PROGRESS'];
                echo "|" . $sel['STSK_TYPE'];

	break;

}






?>