<?php

$usr = $_GET['usr'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
$query = "SELECT STSK_PROGRESS , STSK_ANCIENT_PRO, STSK_ID, STSK_ISS_ID, STSK_TYPE FROM SUBTASKS WHERE (STSK_MAIN_USR = "  . $usr . " AND STSK_PROGRESS != STSK_ANCIENT_PRO)";


$news = mysqli_query($datos, $query);

if (mysqli_num_rows($news) == 0){

$user_out1 = 0;
$user_out2 = 0;
$user_out3 = 0;
$user_out4 = 0;
$user_out5 = 0;
$user_out6 = 0;
$user_out7 = 0;
$user_out8 = 0;

} else {


   $outcome = mysqli_fetch_assoc($news);

if($outcome["STSK_TYPE"] == 1 || $outcome["STSK_TYPE"] == "1" ){

$handler = mysqli_query($datos, "SELECT SUM(STSK_PROGRESS) FROM SUBTASKS WHERE (STSK_ISS_ID = " . $outcome['STSK_ISS_ID'] . " AND STSK_CHARGE_USR != STSK_MAIN_USR AND STSK_TYPE = 1) GROUP BY STSK_CHARGE_USR ");

} else {
$handler = mysqli_query($datos, "SELECT SUM(STSK_PROGRESS) FROM SUBTASKS WHERE (STSK_ISS_ID = " . $outcome['STSK_ISS_ID'] . " AND STSK_CHARGE_USR != STSK_MAIN_USR AND STSK_TYPE = 0) GROUP BY STSK_CHARGE_USR ");

}

      

       $count   = mysqli_num_rows($handler);

       while ($fila = mysqli_fetch_row($handler)){
	         $sum += $fila[0];
          }

        $ctp   = (($sum * 100) / (100 * $count));

        $classText = "";

        if ($ctp >= 99.9 ){

           $classText = "FINALIZADO";

          }

$get_main  = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM SUBTASKS WHERE (STSK_ISS_ID = " . $outcome['STSK_ISS_ID'] . " AND STSK_MAIN_USR = STSK_CHARGE_USR); "));
$query_usr = mysqli_query($datos, "SELECT CONCAT(B.USR_NAME, ' ', B.USR_SURNAME) AS NAME, A.TRF_USER,  A.TRF_SUBJECT, A.TRF_DESCRIPT, A.TRF_ING_DATE, A.TRF_STSK_SRC_ID FROM TRAFFIC A INNER JOIN USERS B ON(A.TRF_USER = B.USR_ID) WHERE A.TRF_STSK_SRC_ID = " . $get_main['STSK_ID'] . " ORDER BY TRF_ID DESC LIMIT 1" );
$user      = mysqli_fetch_assoc($query_usr);

$user_out1 = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($user['NAME']))));
$user_out2 = $user['TRF_USER'];
$user_out3 = $user['TRF_SUBJECT'];
$user_out4 = $user['TRF_DESCRIPT'];
$user_out5 = date('d/m/Y', strtotime($user['TRF_ING_DATE']));
$user_out6 = $user['TRF_STSK_SRC_ID'];
$user_out7 = $ctp;
$user_out8 = $classText;


mysqli_query($datos, "UPDATE SUBTASKS SET STSK_ANCIENT_PRO = " . $outcome['STSK_PROGRESS'] . " WHERE STSK_ID =" . $outcome['STSK_ID']);

}

echo  $user_out1 ;
echo "|" . $user_out2 ;
echo "|" . $user_out3 ;
echo "|" . $user_out4 ;
echo "|" . $user_out5 ;
echo "|" . $user_out6 ;
echo "|" . $user_out7 ;
echo "|" . $user_out8 ;
echo "|" . $outcome['STSK_PROGRESS'] ;
echo "|" . $outcome['STSK_TYPE'];


$sum = 0;


//GEt the Last User that grow up his progress
 


?>