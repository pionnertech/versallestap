<?php

$fac = $_GET['facility'];
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


//global

//distinct
$array_dept = [];
$i = 0;

$query_count_departament = mysqli_query($datos, "SELECT DISTINCT B.USR_DEPT FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID)  WHERE STSK_FAC_CODE = " . $fac . " GROUP BY USR_DEPT;");

while($f1 = mysqli_fetch_row($query_count_departament)){
$array_dept[$i] = $f1[0];
$i = $i + 1;
}

$cant_dept = count($array_dept);


//$query_cantbydept_global = mysqli_query($datos, "SELECT A.STSK_ID, B.USR_DEPT FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID)  WHERE STSK_FAC_CODE = " . $fac . " ORDER BY USR_DEPT " );


//cantidad global por departamento

//obtener la cantidad de requerimientos por tipo

$can_by_cantbydept = mysqli_query($datos, "SELECT COUNT( STSK_ID ) , B.USR_DEPT, A.STSK_STATE FROM SUBTASKS A INNER JOIN USERS B ON ( A.STSK_CHARGE_USR = B.USR_ID ) WHERE STSK_FAC_CODE = " . $fac . " GROUP BY B.USR_DEPT, A.STSK_STATE  ");
$query_dept_global = mysqli_query($datos, "SELECT COUNT(STSK_ID), B.USR_DEPT FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID)  WHERE STSK_FAC_CODE = " . $fac . " GROUP BY USR_DEPT;");
$query_total_subtasks = mysqli_query($datos, "SELECT B.EST_DESCRIPT, COUNT( STSK_ID )  FROM SUBTASKS A INNER JOIN EST B ON ( A.STSK_STATE = B.EST_CODE )  WHERE STSK_FAC_CODE = " . $fac . " GROUP BY EST_DESCRIPT");
$array_cantbydept = mysqli_fetch_array($can_by_cantbydept);


$i = 0;

$limit = mysqli_num_rows($query_total_subtasks);

echo "{ \"data\" : [{\"global\":[";
while ($global1 = mysqli_fetch_row($query_total_subtasks)) {
	echo "{\"" . $global1[0] . "\":\"" . $global1[1] . "\"}";
	$i = $i + 1;
       if($i  < $limit ){
       	echo ",";
       }
       
}

echo "]},";
$x = 0;
mysqli_data_seek($query_dept_global, 0);

while ($deptos = mysqli_fetch_row($query_dept_global)){

$n = 0;
echo "{\"" . $deptos[1] . "\":[";

$handler  = mysqli_query($datos, "SELECT COUNT( STSK_ID ) , B.USR_DEPT, C.EST_DESCRIPT FROM SUBTASKS A INNER JOIN USERS B ON ( A.STSK_CHARGE_USR = B.USR_ID ) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (A.STSK_FAC_CODE = " . $fac . " AND B.USR_DEPT = '" . $array_dept[$x] . "') GROUP BY B.USR_DEPT, A.STSK_STATE ORDER BY B.USR_DEPT" );
$count = mysqli_num_rows($handler);

   while ($fila3 = mysqli_fetch_row($handler)){

      echo "{\"" . $fila3[2] . "\":\"" . $fila3[0] . "\"}";
       
       $n = $n + 1;
      if($n < $count){

      	echo ",";
      }
      
   }
   echo "]}";

   $x = $x + 1;

   if($x < $cant_dept){
   	  echo ",";
   }
}

echo "]}";


?>