<?php

$com = $_GET['com'];
$iss = $_GET['iss'];
$fac = $_GET['fac'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

//first check if the comentary was set ... if is.. then reject it 

$ngix = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ISS_COMENTARY FROM ISSUES WHERE ISS_ID =" . $iss ));

if(!is_null($ngix['ISS_COMENTARY']) ){
	echo "no puede sobrerescribir el comentario";
exit;
}

if(mysqli_query($datos, "UPDATE ISSUES SET ISS_COMENTARY = '" . $com . "' WHERE (ISS_ID =" . $iss ." AND ISS_FAC_CODE = " . $fac . ")")){
	echo "ok!";
} else {
 echo mysqli_error($datos);
}


?>