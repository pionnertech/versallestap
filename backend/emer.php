<?php 

$fac = $_REQUEST['f'];
$dir = "var/www/html/10000/";
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$handler = mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE USR_RANGE = 'admin' OR USR_RANGE = 'sadmin'");

while ($file = mysqli_fetch_row($handler)){
	mkdir($dir . $file[0] . "/");
	mkdir($dir . $file[0] . "_in/");
	mkdir($dir . $file[0] . "_alt/");
	chmod($dir . $file[0] . "/", 0777, true);
	chmod($dir . $file[0] . "_in/", 0777, true);
	chmod($dir . $file[0] . "_alt/", 0777, true);

}

echo "hecho";



?>