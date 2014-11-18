<?php



session_start();


if(isset($_POST["U"]))

{

$datos = mysqli_connect('mysql.nixiweb.com', "u315988979_eque", "MoNoCeRoS", "u315988979_eque");

$query = "SELECT USR_ID, USR_NAME, USR_SURNAME, USR_FACILITY FROM USERS WHERE (USR_NICK = '" . $_POST["U"] . "' AND USR_PASS = '" . $_POST['P'] . "')";

$outcome = mysqli_fetch_assoc(mysqli_query($datos, $query));

if (!$outcome)

{
    $_SESSION["TxtUser"] = "";
    $_SESSION["TxtPass"] = "";
    $_SESSION["TxtFacName"] = "";
    $_SESSION["TxtCode"] = "";

    session_destroy();

echo "<script language='javascript'>window.location='../login.php?t=1'; </script>";


} 


else { 

 $_SESSION["TxtCode"] = $outcome['USR_ID'];
 $_SESSION["TxtUser"] = $outcome['USR_NAME'];
 $_SESSION["TxtPass"] = $outcome['USR_SURNAME'];
 $_SESSION["TxtFacility"] = $outcome['USR_FACILITY'];

 echo "<script language='javascript'>window.location='../index.php'</script>";

} 


} else {

	echo "<script language='javascript'>window.location='../login.php?t=1'; </script>";
}

 ?>


