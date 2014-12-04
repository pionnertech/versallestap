<?php

session_start();

if(isset($_SESSION['TxtCode'])){

echo "<script language='javascript'>window.location='Front/activity.php'</script>";

} else {

?>
<!DOCTYPE html><html lang="es" class="no-js"> <head> <meta charset="utf-8"> <title>EQUE - Sistemas</title> <style type="text/css"> #logo{ position:relative; top:-2em; } #logo > img{ max-width: 5em; } </style> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta name="description" content=""> <meta name="author" content=""> <!-- CSS --> <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=PT+Sans:400,700'> <link rel="stylesheet" href="assets/css/reset.css"> <link rel="stylesheet" href="assets/css/supersized.css"> <link rel="stylesheet" href="assets/css/style.css"> <!-- HTML5 shim, for IE6-8 support of HTML5 elements --> <!--[if lt IE 9]> <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> <![endif]--> </head> <body> <div class="page-container"> <h1 id="logo"><img src="assets/img/logo.png"></h1> <h2>Accesso al Sistema</h2> <form action="backend/coverflow.php" method="post"> <input type="text" name="U" class="username" placeholder="Usuario"> <input type="password" name="P" class="password" placeholder="Password"> <button type="submit">Ingresar</button> <div class="error"><span></span></div> </form> </div> <!-- Javascript --> <script src="assets/js/jquery-1.8.2.min.js"></script> <script src="assets/js/supersized.3.2.7.min.js"></script> <script src="assets/js/supersized-init.js"></script> <script src="assets/js/scripts.js"></script> </body></html>
<?
}
?>