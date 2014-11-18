<?php

session_start();

 $_SESSION["TxtUser"] = "";
 $_SESSION["TxtPass"] = "";
 $_SESSION["TxtCode"] = "";
 $_SESSION["TxtFacility"] = "";


session_destroy();

echo "<script language='javascript'>window.location='../login.php'</script>";


 ?>