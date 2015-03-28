<?php

    $uNam = $_GET["uNam"];
    $uSur = $_GET["uSur"]; 
    $uDep = $_GET["uDep"];
    $uEma = $_GET["uEma"];
    $uRan = $_GET["uRan"];
    $uNic = $_GET["uNic"];
    $uPas = $_GET["uPas"];
    $uPas = $_GET["uTim"];


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = "INSERT INTO USERS (USR_NAME, USR_SURNAME, USR_RANGE, USR_NICK, USR_PASS, USR_MAIL, USR_DEPT, USR_CREATE_DATE) " . 
         " VALUES('" . $uNam . "', '" . $uSur . "', '" . $uRan . "', '" . $uNic . "', '" . $uPas . "', '" . $uEma . "', '" . $uDep . "' , '" . $uTim . "');";

if(mysqli_query($datos, $query)){
echo mysql_insert_id($datos);
} else {
   echo mysqli_error($datos);
}




?>