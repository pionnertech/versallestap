<?php

$user_id = $_GET['user_id'];
$dept = str_replace("_", " " , $_GET['dept']);
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
if($user_id != "General"){

$graph_query = " SELECT B.EST_COLOR, B.EST_DESCRIPT, COUNT( A.STSK_ID ) , " .
               " ROUND((COUNT( A.STSK_ID ) / total) *100) AS percentage " .
               " FROM SUBTASKS A RIGHT JOIN EST B ON " .
               " ( A.STSK_STATE = B.EST_CODE AND A.STSK_CHARGE_USR = " . $user_id . ") " .
               " CROSS JOIN ( SELECT COUNT(STSK_ID )" .
               "  AS total FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $user_id . " ) x GROUP BY 1 ORDER BY EST_CODE";

 /*$queryStr = "SELECT COUNT( STSK_ID ) , B.USR_DEPT, C.EST_DESCRIPT, C.EST_COLOR FROM SUBTASKS A INNER JOIN" . 
                " USERS B ON ( A.STSK_CHARGE_USR = B.USR_ID ) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) " . 
                " WHERE (A.STSK_FAC_CODE = " . $fac . " AND B.USR_DEPT = '" . $array_dept[$i] . "') GROUP BY B.USR_DEPT, A.STSK_STATE ORDER BY B.USR_DEPT"*/

                $graph = mysqli_query($datos, $graph_query);

                while ( $fila = mysqli_fetch_row($graph)){
                        echo  $fila[2] . "/" . $fila[3] . "/";
                      }

} else {

$x = 0;



   $graph_queryi = "SELECT COUNT(A.STSK_ID), C.EST_CODE FROM SUBTASKS A " .
"RIGHT JOIN EST C ON (C.EST_CODE = A.STSK_STATE) " .
"INNER JOIN USERS B ON(B.USR_ID = A.STSK_CHARGE_USR AND B.USR_DEPT = '" . $dept . "' AND B.USR_FACILITY = " . $fac . ") " .
" GROUP BY C.EST_CODE ORDER BY C.EST_CODE ";



       $graph = mysqli_query($datos, $graph_queryi);

       while($cuenta = mysqli_fetch_row($graph)){
       	   $x += $cuenta[0];
       }  
          
       $inner_query = mysqli_query($datos, $graph_queryi);
  
        while ( $fila2 = mysqli_fetch_array($inner_query)){
          echo $fila2[0] . "/" . round(($fila2[0]/$x) * 100) . "/"; 
        }
   

}



?>