<?php

$user_id = $_GET['user_id'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$graph_query = " SELECT B.EST_COLOR, B.EST_DESCRIPT, COUNT( A.STSK_ID ) , " .
               " ROUND((COUNT( A.STSK_ID ) / total) *100) AS percentage " .
               " FROM SUBTASKS A RIGHT JOIN EST B ON " .
               " ( A.STSK_STATE = B.EST_CODE AND A.STSK_CHARGE_USR = " . $user_id . ") " .
               " CROSS JOIN ( SELECT COUNT(STSK_ID )" .
               "  AS total FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $user_id . " ) x GROUP BY 1 ORDER BY EST_CODE";

$graph = mysqli_query($datos, $graph_query);


while ( $fila = mysqli_fetch_row($graph)){
     echo $fila[0] . "/" . $fila[1] . "/" . $fila[2] . "/" . $fila[3];
}

?>