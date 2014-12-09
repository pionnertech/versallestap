<?php


session_start();


if(isset($_POST["U"]))

{

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query = "SELECT USR_ID, USR_NAME, USR_SURNAME, USR_FACILITY, USR_RANGE FROM USERS WHERE (USR_NICK = '" . $_POST["U"] . "' AND USR_PASS = '" . $_POST['P'] . "')";

$outcome = mysqli_fetch_assoc(mysqli_query($datos, $query));

if (!$outcome)

{
    $_SESSION["TxtUser"] = "";
    $_SESSION["TxtPass"] = "";
    $_SESSION["TxtFacility"] = "";
    $_SESSION["TxtCode"] = "";
    $_SESSION["TxtRange"] = "";

    session_destroy();

echo "<script language='javascript'>window.location='../index.php?t=1'; </script>";


} 


else { 

 $_SESSION["TxtUser"] = $outcome['USR_NAME'];
 $_SESSION["TxtPass"] = $outcome['USR_SURNAME'];
 $_SESSION["TxtFacility"] = $outcome['USR_FACILITY'];
 $_SESSION["TxtCode"] = $outcome['USR_ID'];
 $_SESSION["TxtRange"] = $outcome['USR_RANGE'];


switch ($outcome['USR_RANGE']) {
	case "admin":
		 echo "<script language='javascript'>window.location='../Admin/index.php'</script>";
		break;
	case "front-user":
	    echo "<script language='javascript'>window.location='../Front/activity.php'</script>";
		break;
	case "back-user":
	     echo "<script language='javascript'>window.location='../Back/other-user-profile.php'</script>";
	break;

}

} 

} else {

	echo "<script language='javascript'>window.location='../index.php?t=1'; </script>";
}

 ?>


