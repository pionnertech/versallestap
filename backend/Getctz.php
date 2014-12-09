<?php

$rut = $_GET['rut'];
$fac = $_GET['fac'];


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


$query = mysqli_query($datos, "SELECT * FROM CITIZENS WHERE CTZ_RUT = " . $rut);

if (mysqli_num_rows($query) === 0){

echo 0;

} else {

$outcome = mysqli_fetch_assoc($query);

echo  $outcome['CTZ_NAMES'] . "," . $outcome['CTZ_SURNAME1'] . "," . $outcome['CTZ_SURNAME2'] . "," . $outcome['CTZ_ADDRESS'] . "," . $outcome['CTZ_GEOLOC'] . "," . $outcome['CTZ_RUT'] . "," . $outcome['CTZ_DVF'] . "," . $outcome['CTZ_DATE_ING'] . "," . $outcome['CTZ_TEL'] . "," . $outcome['CTZ_MAIL'];

}




?>