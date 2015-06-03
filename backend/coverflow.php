<?php
session_start();

if(isset($_POST["U"]))

{

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = "SELECT A.USR_ID, A.USR_NAME, A.USR_SURNAME, A.USR_FACILITY, A.USR_RANGE, A.USR_DEPT, A.USR_CHARGE, B.FAC_NAME FROM USERS A INNER JOIN FACILITY B ON(A.USR_FACILITY = B.FAC_CODE) WHERE (USR_NICK = '" . $_POST["U"] . "' AND USR_PASS = '" . $_POST['P'] . "')";

$outcome = mysqli_fetch_assoc(mysqli_query($datos, $query));

if (!$outcome)

{
    $_SESSION["TxtUser"] = "";
    $_SESSION["TxtPass"] = "";
    $_SESSION["TxtFacility"] = "";
    $_SESSION["TxtCode"] = "";
    $_SESSION["TxtRange"] = "";
    $_SESSION["TxtPosition"] = "";
    $_SESSION["TxtFacName"] = "";

    session_destroy();

echo "<script language='javascript'>window.location='../index.php?t=1'; </script>";

} 


else { 

 $_SESSION["TxtUser"]     = $outcome['USR_NAME'];
 $_SESSION["TxtPass"]     = $outcome['USR_SURNAME'];
 $_SESSION["TxtFacility"] = $outcome['USR_FACILITY'];
 $_SESSION["TxtCode"]     = $outcome['USR_ID'];
 $_SESSION["TxtRange"]    = $outcome['USR_RANGE'];
 $_SESSION["TxtDept"]     = $outcome['USR_DEPT'];
 $_SESSION["TxtPosition"] = $outcome['USR_CHARGE'];
 $_SESSION["TxtFacName"]  = $outcome['FAC_NAME'];


switch ($outcome['USR_RANGE']) {
	
	case "sadmin":
	echo "<script language='javascript'>window.location='../Sadmin/index.php'</script>";
	break;
	case "admin":
		 echo "<script language='javascript'>window.location='../Admin/other-user-profile.php'</script>";
		break;
	case "front-user":
	    echo "<script language='javascript'>window.location='../Front/activity.php'</script>";
		break;
	case "back-user":
	     echo "<script language='javascript'>window.location='../Back/other-user-profile.php'</script>";
	break;
	case "rrhh":
	     echo "<script language='javascript'>window.location='../Resources/user-manager.php'</script>";
	break;

}

} 

} else {

	echo "<script language='javascript'>window.location='../index.php?t=1'; </script>";
}

 ?>


