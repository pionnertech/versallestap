<?php


$fac = $_GET['fac'];
$file_name = $_GET['file_name'];
$main_usr_id = $_GET['main_usr_id'];
$charge_usr_id = $_GET['charge_usr_id'];

//first check if exist
$dir = "/var/www/html/" . $fac . "/";

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$dept = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_DEPT FROM USERS WHERE (USR_ID = " . $main_usr_id . " AND USR_FACILITY= " . $fac . ")"));

if($charge_usr_id == "Mi Departamento"){
   
   $team_ids = mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE (USR_DEPT = " . $dept['USR_DEPT'] . " AND USR_FACILITY = " . $fac . " AND USR_RANGE = 'back-user')");

       while($fila = mysqli_fetch_row($team_ids)){
                 
                 if(!is_dir($dir . $fila[0] . "/")){
	                    mkdir($dir . $fila[0] . "/", 0775, true);
                    }
                 if(!is_file($dir . $main_usr_id . "/" . $file_name)){
	                    echo "no file!";
	                    exit;
                    }
                 copy($dir . $main_usr_id . "/" . $file_name, $dir . $fila[0] . "/" . $file_name );
            }

} else {


$team = explode(",", $charge_usr_id);

for($i=0; $i < count($team); $i++){

 $usr_id_q = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE (CONCAT(USR_NAME, ' ' , USR_SURNAME) = '" . strtoupper($team[$i]) . "' AND USR_FACILITY = " . $fac .")"));

                  if(!is_dir($dir . $usr_id_q['USR_ID'] . "/")){
	                    mkdir($dir . $fila[0] . "/", 0775, true);
                    }
                 if(!is_file($dir . $main_usr_id . "/" . $file_name)){
	                    echo "no file!";
	                    exit;
                    }

             copy($dir . $main_usr_id . "/" . $file_name, $dir . $usr_id_q['USR_ID'] . "/" . $file_name );

}

}




?>