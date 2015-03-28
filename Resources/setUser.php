<?php

    $uNam = $_GET["uNam"];
    $uSur = $_GET["uSur"]; 
    $uDep = $_GET["uDep"];
    $uEma = $_GET["uEma"];
    $uRan = $_GET["uRan"];
    $uNic = $_GET["uNic"];
    $uPas = $_GET["uPas"];
    $uTim = $_GET["uTim"];
    $uFac = $_GET["uFac"];


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = "INSERT INTO USERS (USR_NAME, USR_SURNAME, USR_RANGE, USR_NICK, USR_PASS, USR_MAIL, USR_DEPT, USR_CREATE_TIME, USR_FACILITY) " . 
         " VALUES('" . $uNam . "', '" . $uSur . "', '" . $uRan . "', '" . $uNic . "', '" . $uPas . "', '" . $uEma . "', '" . $uDep . "' , '" . $uTim . "' , " . $uFac .");";

$handler = mysqli_query($datos, $query);

if($handler){
echo mysqli_insert_id($datos);
} else {
   echo mysqli_error($datos);
}

?>