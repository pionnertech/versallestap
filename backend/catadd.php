<?php 

$des = $_GET['des'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

if(!mysqli_query($datos, "INSERT INTO CAT (CAT_DESCRIPT, CAT_FAC) VALUES ('" . $des . "', " . $fac . ")")){

    echo "0";

} else {
	
   $id_i =  mysqli_fetch_assoc(mysqli_query($datos, "SELECT CAT_ID FROM `CAT` WHERE CAT_FAC = " . $fac . " ORDER BY CAT_ID DESC LIMIT 1"));
	echo $id_i['CAT_ID'];
}

