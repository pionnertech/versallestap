<?php

$iss_id = $_GET['iss_id'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = "SELECT A.ISS_DESCRIP, " .
"A.ISS_SUBJECT, " .
"A.ISS_DATE_ING," .
"A.ISS_FINISH_DATE, " .
"A.ISS_PROGRESS, " .
"C.EST_DESCRIPT, " .
"B.USR_NAME ,  " .
"B.USR_SURNAME,  " .
"D.CTZ_NAMES,  " .
"D.CTZ_SURNAME1 " .
"FROM  " .
"ISSUES A INNER JOIN USERS B ON(A.ISS_CHARGE_USR = USR_ID)" .
"INNER JOIN EST C ON(C.EST_CODE =  A.ISS_STATE)" .
"INNER JOIN CITIZENS D ON(D.CTZ_RUT = A.ISS_CTZ ) " .
"WHERE (ISS_FAC_CODE = " . $fac . " AND ISS_ID = " . $iss_id . ")";


$handler = mysqli_query($datos, $query);

$outcome = "";

while ($fila = mysqli_fetch_row($handler)) {
	
	for ($i=0; $i < count($fila); $i++) { 
		$outcome .= $fila[$i] . "|";
	}
   
}

echo $outcome;

?>
