<?php

$fac = $_GET['facility'];
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


//global

//distinct
$array_dept = [];
$i = 0;

$query_count_departament = mysqli_query($datos, "SELECT DISTINCT USR_DEPT FROM USERS WHERE USR_FACILITY = " . $fac . " ORDER BY USR_DEPT");

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
$array_cantbydept = mysqli_fetch_array($can_by_cantbydept);

$i = 0;
echo "{ \"data\" : [{\"global\":[";

while ($global1 = mysqli_fetch_row($query_dept_global)) {
	echo "{\"d" . $i . "\":\"" . $global1[0] . "\"}";
       if($i  < $cant_dept ){
       	echo ",";
       }
       $i = $i + 1;
}

echo "]},";
$x = 0;
mysqli_data_seek($query_dept_global, 0);
while ($deptos = mysqli_fetch_row($query_dept_global)){
$n = 0;
echo "{\"de" . $x . "\":[";
$handler  = mysqli_query($datos, "SELECT COUNT( STSK_ID ) , B.USR_DEPT, C.STSK_STATE FROM SUBTASKS A INNER JOIN USERS B ON ( A.STSK_CHARGE_USR = B.USR_ID ) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (A.STSK_FAC_CODE = " . $fac . " AND B.USR_DEPT = '" . $array_dept[$x] . "' GROUP BY B.USR_DEPT, A.STSK_STATE ORDER BY A.USR_DEPT" );
$counter = mysql_num_rows($handler);	
   while($fila3 = mysqli_fetch_row($handler)){
      echo "{\"" . $fila3[2] . "\":\"" . $fila[0] . "\"}";
      if($n < $counter){
      	echo ",";
      }
       $n = $n +1;
   }
   echo "]}";
   if($x < $cant_dept){
   	  echo ",";
   }
}

echo "]}";







//statics

/*
a. Carga de trabajo de funcionarios

b. % de cumplimiento por trabajos asignados

c. % de cumplimiento por área o departamento

d. Ranking de cumplimiento (los 5 más cumplidores)

e. Ranking de incumplimiento (los 5 más incumplidores)

f. Ranking de requerimientos más demandados

g. Tiempos promedios en cumplir requerimientos por temas

h. Comunas que más realizan requerimientos.
*/
?>