<?php 

$fac    = $_REQUEST['fac'];
$user   = $_REQUEST['usr'];
$fname  = $_REQUEST['fname'];
$type   = $_REQUEST['type'];
$stsk   = $_REQUEST['stsk'];

// who is the main User???
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
$query = mysql_fetch_assoc(mysqli_query($datos, "SELECT A.USR_ID  FROM USERS A CROSS JOIN(SELECT A.USR_DEPT as DEPT FROM USERS A WHERE A.USR_ID = " . $user . "  ) B ON(A.USR_RANGE = 'admin' AND A.USR_DEPT = B.DEPT)"));

$admin = $query['USR_ID'];


if($type == 0) {
	if($admin != $user){
        $dir = "var/www/html/" . $fac . "/" . $user . "_in/";
	} else {
		$dir = "var/www/html/" . $fac . "/" . $user . "/";
	}    
} else {
	if($admin != $user){
        $dir = "var/www/html/" . $fac . "/" . $admin . "_alt/";
	} else {
		$dir = "var/www/html/" . $fac . "/" . $user . "_alt/";
	}
}

$extension = pathinfo($fname, PATHINFO_EXTENSION);
$var = basename($fname, "." . $extension);

unlink($dir . $var . "_[" . $stsk . "]_" . $user . "." . $extension);
mysqli_close($datos);

?>