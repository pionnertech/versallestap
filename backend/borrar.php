<?php

$keyfile= $_REQUEST['keyfile'];
$fac = 10000;

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$uteam = mysqli_query($datos, "SELECT A.USR_ID, B.STSK_ID FROM USERS A INNER JOIN SUBTASKS B ON(A.USR_ID = B.STSK_CHARGE_USR AND B.STSK_ISS_ID = 372 AND B.STSK_ISS_ID <> B.STSK_ID) WHERE (STSK_FAC_CODE =" . $fac . " AND STSK_TYPE= 1)");
 
    if($hdir = opendir("/var/www/html/" . $fac . "/_tmp/")) {

      while (false !== ($files = readdir($hdir))) {
        
     	  if(preg_match_all("/_\[" . $keyfile . "\]_/", $files) == 1){

     	 	  $extension = pathinfo($files, PATHINFO_EXTENSION);   

              while($uteams = mysqli_fetch_row($uteam)){
                echo $dir . $uteams[0] . "_alt/" . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_[" . $uteams[1] . "]_." . $extension;
                if(copy("/var/www/html/" . $fac . "/_tmp/" . $files ,  $dir . $uteams[0] . "_alt/" . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_[" . $uteams[1] . "]_." . $extension)){
                    
                   unlink("/var/www/html/" . $fac . "/_tmp/" . $files);

                  }

              }
              mysqli_data_seek($uteam, 0);
        }
      }
    }
    closedir($hdir);

?>