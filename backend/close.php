<?php

session_start();

 $_SESSION["TxtUser"] = "";
 $_SESSION["TxtPass"] = "";
 $_SESSION["TxtCode"] = "";
 $_SESSION["TxtFacility"] = "";
 $_SESSION["TxtRange"] = "";

session_destroy();

echo "<script language='javascript'>window.location='../index.php'</script>";


 ?>