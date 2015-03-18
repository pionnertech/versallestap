<?php
$iss_stsk    = $_GET['st'];
$percent     = $_GET['per'];
$stsk_src_id = $_GET['srcid'];
$usr         = $_GET['usr'];

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

while (true) {

$news = mysqli_query($datos, "SELECT STSK_PROGRESS , STSK_ANCIENT_PRO, STSK_ID, STSK_ISS_ID FROM SUBTASKS WHERE (STSK_MAIN_USR = "  . $usr . " AND STSK_PROGRESS != STSK_ANCIENT_PRO)");

if (mysqli_num_rows($news) == 0){

     sleep(1);

$user_out1 = "";
$user_out2 = "";
$user_out3 = "";
$user_out4 = "";
$user_out5 = "";
$user_out6 = "";
$user_out7 = "";
$user_out8 = "";

} else {


   $outcome = mysqli_fetch_assoc($news);

       mysqli_query($datos, "UPDATE SUBTASKS SET STSK_ANCIENT_PRO = " . $outcome['STSK_PROGRESS'] . " WHERE STSK_ID =" . $outcome['STSK_ID']);

       $handler = mysqli_query($datos, "SELECT SUM(STSK_PROGRESS) FROM SUBTASKS WHERE (STSK_ISS_ID = " . $outcome['STSK_ISS_ID'] . " AND STSK_CHARGE_USR != STSK_MAIN_USR) GROUP BY STSK_CHARGE_USR ");
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

sleep(1);



$user_out1 = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($user['NAME']))))
$user_out2 = $user['TRF_USER'];
$user_out3 = $user['TRF_SUBJECT'];
$user_out4 = $user['TRF_DESCRIPT'];
$user_out5 = date('d/m/Y', strtotime($user['TRF_ING_DATE']));
$user_out6 = $user['TRF_STSK_SRC_ID'];
$user_out7 = $ctp;
$user_out8 = $classText;

}


echo "data :" . $user_out1 .  "\n";
echo "data :" . $user_out2 .  "\n";
echo "data :" . $user_out3 .  "\n";
echo "data :" . $user_out4 .  "\n";
echo "data :" . $user_out5 .  "\n";
echo "data :" . $user_out6 .  "\n";
echo "data :" . $user_out7 .  "\n";
echo "data :" . $user_out8 .  "\n\n";

ob_end_flush();
flush();

$sum = 0;


//GEt the Last User that grow up his progress
 


}



?>