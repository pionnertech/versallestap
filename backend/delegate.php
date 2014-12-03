<?php


$fac = $_GET['fac'];
$nombre = $_GET['name'];
$surname = $_GET['surmane'];
$importance = $_GET['imp'];
$msg = $_GET['msg'];



$datos = mysqli_connect('mysql.nixiweb.com', "u315988979_eque", "MoNoCeRoS", "u315988979_eque");

$query = mysqli_query($datos, "SELECT FROM USERS ")


?>