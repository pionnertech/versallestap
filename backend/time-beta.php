<?php

//time nuevo para la escucha de progresos

$usr = $_GET['usr'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
//who is listening?
$ident = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_RANGE AS RAN FROM USERS WHERE USR_ID = " . $usr));
//aqui se detectan los diferenciales de progreso independiente de que origan sean 
$first = mysqli_query($datos, "SELECT A.STSK_PROGRESS, A.STSK_ANCIENT_PRO, A.STSK_ID, A.STSK_ISS_ID, A.STSK_TYPE, B.USR_RANGE, A.STSK_TICKET, CONCAT(B.USR_NAME, ' ', B.USR_SURNAME) AS NAME, A.STSK_CHARGE_USR, A.STSK_OVER FROM SUBTASKS  A INNER JOIN USERS B ON (B.USR_ID = A.STSK_CHARGE_USR) WHERE (STSK_CHARGE_USR <> " . $usr . " AND STSK_FAC_CODE = " . $fac . " AND STSK_MAIN_USR = " . $usr . " AND STSK_PROGRESS <> STSK_ANCIENT_PRO ) ORDER BY STSK_ID DESC LIMIT 1; ");
//si no encuentra, salga
if(mysqli_num_rows($first) < 1){

    echo "0|0|0|0|0|0|0|0|0|0|0"; 
    exit;
} 

//si encuentra, veamos de que se trata., de quien para quien...
//discriminemos origen y tipo
$sel = mysqli_fetch_assoc($first);

if($sel['STSK_CHARGE_USR'] == $usr){

	echo "0|0|0|0|0|0|0|0|0|0|1"; 

    exit;
}

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
       mysqli_query($datos, "UPDATE SUBTASKS SET STSK_ANCIENT_PRO = " . $sel ['STSK_PROGRESS'] . " WHERE STSK_ID = " . $sel["STSK_ID"]);
       //get the gral avg
	   $total = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(CASE WHEN STSK_PROGRESS IS NULL THEN 0 ELSE STSK_PROGRESS END)) AS AVX FROM SUBTASKS WHERE (STSK_TICKET = '" . $sel['STSK_TICKET'] . "' AND STSK_CHARGE_USR <> STSK_MAIN_USR AND STSK_FAC_CODE = " . $fac . " AND STSK_MAIN_USR =  " . $usr . ")" ));
       //get the INFO about the partial progress
       $trf_str = mysqli_fetch_assoc(mysqli_query($datos, "SELECT * FROM TRAFFIC WHERE TRF_STSK_ID = " . $sel['STSK_ID'] . " ORDER BY TRF_ID DESC"));
       //get the REAL TRF_ISS_SRC_ID depending of STSK_OVER
         $min  = mysqli_fetch_assoc(mysqli_query($datos, "SELECT MIN(STSK_ID) AS MIN FROM SUBTASKS WHERE (STSK_TICKET = '" . $sel['STSK_TICKET'] . "' AND STSK_FAC_CODE = " . $fac .") "));
        //get the OVER...
         $over = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_OVER FROM SUBTASKS WHERE (STSK_ID = " . $min['MIN'] . " AND STSK_FAC_CODE = " . $fac. ")" ));
          


          if($over['STSK_OVER'] == 1 && $ident['RAN'] !== 'sadmin'){



            
             $real = $min['MIN'];
          } else {
             $real = $trf_str['TRF_STSK_SRC_ID'];
          }
       
                echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($sel['NAME']))));
                echo "|" . $trf_str['TRF_USER'];
                echo "|" . $trf_str['TRF_SUBJECT'];
                echo "|" . $trf_str['TRF_DESCRIPT'];
                echo "|" . date('d/m/Y', strtotime( $trf_str['TRF_ING_DATE']));
                echo "|" . $real;
                echo "|" . $total['AVX'];
                echo "|" . $classText;
                echo "|" . $trf_str['TRF_STSK_ID']; 
                echo "|" . $sel['STSK_TYPE'];
                echo "|" . $sel['STSK_PROGRESS'];
		break;
	
	case 1:
	   //se saca el promedio y se obtien el valor parcial  
       //  =======  caso back =======//
       if($sel['STSK_PROGRESS'] > 99.9){  
           	$classText = "FINALIZADO";  } else { 
            $classText = "" ;
        }
	     //update the difference , set the traffic and variables
       
       mysqli_query($datos, "UPDATE SUBTASKS SET STSK_ANCIENT_PRO = " . $sel ['STSK_PROGRESS'] . " WHERE STSK_ID = " . $sel["STSK_ID"]);
       
       //get the gral avg
       if($ident['RAN'] == 'sadmin'){

        $total = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(CASE WHEN A.STSK_PROGRESS IS NULL THEN 0 ELSE A.STSK_PROGRESS END)) AS AVX FROM SUBTASKS A INNER JOIN USERS B ON(B.USR_ID = A.STSK_CHARGE_USR) WHERE (STSK_TICKET = '" . $sel['STSK_TICKET'] . "' AND STSK_FAC_CODE = " . $fac . "  AND B.USR_RANGE = 'admin')")); } else {
        $total = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(CASE WHEN STSK_PROGRESS IS NULL THEN 0 ELSE STSK_PROGRESS END)) AS AVX FROM SUBTASKS WHERE (STSK_TICKET = '" . $sel['STSK_TICKET'] . "' AND STSK_FAC_CODE = " . $fac . " AND STSK_MAIN_USR =  " . $usr . ")" ));
       
       }

	     
       //get the INFO about the partial progress
       $trf_str = mysqli_fetch_assoc(mysqli_query($datos, "SELECT * FROM TRAFFIC_II WHERE TII_STSK_ID = " . $sel['STSK_ID'] . " ORDER BY TII_ID DESC"));
       
                echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($sel['NAME']))));//0
                echo "|" . $trf_str['TII_USER'];//1
                echo "|" . $trf_str['TII_SUBJECT'];//2
                echo "|" . $trf_str['TII_DESCRIPT'];//3
                echo "|" . date('d/m/Y', strtotime($trf_str['TII_ING_DATE']));//4
                echo "|" . $trf_str['TII_STSK_SRC_ID'];//5
                echo "|" . $total['AVX'];//6
                echo "|" . $classText;//7
                echo "|" . $trf_str['TII_STSK_ID'];//8 
                echo "|" . $sel['STSK_TYPE'];//9
                echo "|" . $sel['STSK_PROGRESS'];//10
	break;

}


?>