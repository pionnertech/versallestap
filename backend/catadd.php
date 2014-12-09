<?php 

$des = $_GET['des'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

if(!mysqli_query($datos, "INSERT INTO CAT (CAT_DESCRIPT, CAT_FAC) VALUES ('" . $des . "', " . $fac . ")"){

    echo "0";

} else {

	echo "1";
}

