<?php 

$fac    = $_REQUEST['fac'];
$user   = $_REQUEST['usr'];
$fname  = $_REQUEST['fname'];
$type   = $_REQUEST['type'];


// who is the main User???
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
$query = mysqli_query($datos, "SELECT A.USR_ID  FROM USERS A CROSS JOIN(SELECT A.USR_DEPT as DEPT FROM USERS A WHERE A.USR_ID = " . $user . "  ) B ON(A.USR_RANGE = 'admin' AND A.USR_DEPT = B.DEPT)");

$admin = $query['USR_ID'];

if($type == 0) {

 $dir = "var/www/html/" . $fac . "/" . $user . "_in/";

} else {

 $dir = "var/www/html/" . $fac . "/" . $user . "_alt/";

}

   
unlink($dir . $fname);
mysqli_close($datos);

?>