<?php


$fac = $_GET['facility'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

//deptos
$array_dept = [];
$i = 0;

$query_count_departament = mysqli_query($datos, "SELECT DISTINCT B.USR_DEPT FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID)  WHERE STSK_FAC_CODE = " . $fac . " GROUP BY USR_DEPT;");

while($f1 = mysqli_fetch_row($query_count_departament)){
$array_dept[$i] = $f1[0];
$i = $i + 1;
}

$cant_dept = count($array_dept);


// Build the JSON Object as String 

echo "{ \"data\": [{";

//iterate each department

for($i=0; $i < $cant_dept; $i++){

$queryStr =  "SELECT COUNT( STSK_ID ) , B.USR_DEPT, C.EST_DESCRIPT, C.EST_COLOR FROM SUBTASKS A INNER JOIN USERS B ON ( A.STSK_CHARGE_USR = B.USR_ID ) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (A.STSK_FAC_CODE = " . $fac . " AND B.USR_DEPT = '" . $array_dept[$i] . "') GROUP BY B.USR_DEPT, A.STSK_STATE ORDER BY B.USR_DEPT";
$handler_general = mysqli_query($datos, $queryStr);
echo "\"" . $array_dept[$i] . "\":[{";

$query_just = "SELECT USR_NAME FROM USERS WHERE ( USR_FACILITY =" . $fac . " AND USR_DEPT = '" .  $array_dept[$i]  . "')";
$just_hand = mysqli_query($datos, $query_just);

$x = 0;
$per_array = [];
 while($fila3 = mysqli_fetch_row($just_hand)){
 	 $per_array[$x] = $fila3[0];
 	 $x = $x + 1;
 }

echo "\"" . $per_array[$i] . "\":[";

//each personal
$queryPer = "SELECT COUNT(A.STSK_ID), B.EST_COLOR,  B.EST_DESCRIPT FROM SUBTASKS A INNER JOIN EST B ON(A.STSK_STATE = B.EST_CODE) INNER JOIN USERS C ON(A.STSK_CHARGE_USR = C.USR_ID) WHERE (STSK_FAC_CODE = " . $fac . " AND USR_DEPT = '" . $array_dept[$i] . "') GROUP BY USR_NAME, EST_DESCRIPT";
$per_hand = mysqli_query($datos, $queryPer);
$limit = mysqli_num_rows($per_hand);

$y = 0;

  while($fila2 = mysqli_fetch_row($per_hand)){

      echo "{\"label\":\"" . $fila2[2]  . "\", \"data\": \"" . $fila2[0] . "\", \"color\":\"" . $fila2[1] . "\"}";
 
      $y = $y + 1;

    if($y == $limit -1 ){
      echo ",";
    } else {
    	
    }
  }

echo "],";

$z = 0;
$top = mysqli_num_rows($handler_general);
echo "\"General\" : [";

while ($fila4 = mysqli_fetch_row($handler_general) ){
  
   echo "{\"label\":\"" . $fila4[2]  . "\", \"data\":\"" . $fila4[0] . "\", \"color\": \"" . $fila4[3] . "\"}";
   
   $z = $z + 1;

   if($z == $top -1 ){
       echo ",";
    } else {
     
    }
}


echo "]}]}";

unset($per_array);


if ($i == $cant_dept -1){

} else {

   echo ",";
}

}

echo "]}";

?>
