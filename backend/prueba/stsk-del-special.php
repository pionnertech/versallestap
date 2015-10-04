<?php

        $muser   = $_GET['muser'];
        $usrs    = $_GET['usrs'];
        $subject = $_GET['subject'];
        $descript= $_GET['descript'];
        $startD  = $_GET['startD'];
        $fechaF  = date('Y-m-d h:i:s', strtotime(str_replace("/","-",$_GET['fechaF'])));
        $fac     = $_GET['fac'];
        $keyfile = $_GET['keyfile'];
        $uso     = "";



$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
//get ticket 
$ticket = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(COUNT(ISS_ID)) AS TK FROM ISSUES WHERE ISS_FAC_CODE = " . $fac  ));
//insertar ISSUE patron  ...  todo identico en el stsk and iss
$handle_iss = "INSERT INTO ISSUES (ISS_SUBJECT, ISS_DESCRIP, ISS_CHARGE_USR,ISS_FINISH_DATE, ISS_TYPE, ISS_CTZ, ISS_FAC_CODE, ISS_PROGRESS, ISS_LOCK, ISS_TICKET, ISS_COMENTARY) VALUES ";
$handle_iss .= " ('" . $subject . "', '" . $descript . " ', " . $muser . ", '" . $fechaF . "', 0 , 00000000 , " . $fac . " , 0, 1, 'EX0000" . $ticket['TK'] . "', NULL)";
//insertando
if(!mysqli_query($datos, $handle_iss)){
   mysqli_error($datos);
}

$last = mysqli_insert_id($datos);

//insertando el subtask ...
$handle_stsk = "INSERT INTO SUBTASKS (STSK_ISS_ID, STSK_SUBJECT, STSK_DESCRIP, STSK_CHARGE_USR, STSK_STATE, STSK_FINISH_DATE, STSK_MAIN_USR, STSK_FAC_CODE, STSK_PROGRESS, STSK_ANCIENT_PRO, STSK_LOCK, STSK_TYPE, STSK_TICKET, STSK_RESP, STSK_OVER) VALUES ";

switch ($usrs) {
  case 'Jefaturas':

    $team_admin = mysqli_query($datos,"SELECT USR_ID, CONCAT(USR_NAME , ' ' , USR_SURNAME) AS NAME FROM USERS WHERE (USR_FACILITY = " . $fac . " AND USR_RANGE = 'admin' )");
        
        //vaciamos la variable usrs para que pueda tener los otros datos en limpio

        $uso = "";
        $i=0;
        while($fila = mysqli_fetch_row($team_admin)){

              $handle_stsk .= " ( " . $last . ", '" . $subject ."' , '" . $descript. "', " . $fila[0] . ", 2, '" . $fechaF. "', " . $muser . " , " . $fac . ", NULL, 0, 1, 0, 'EX0000" . $ticket['TK'] . "', 2, 1) ";
              $outcome .= $fila[0] . "|";
              $usrs  .= $fila[1] . ",";

        
          $i = $i +1;

          if($i < mysqli_num_rows($team_admin)){
              $handle_stsk .= ", ";
          }
        }

    break;
  
  default:


  $uso = explode(",", $usrs);

  for($i=0 ;$i< count($uso); $i++){

        $ui =  mysqli_fetch_assoc(mysqli_query($datos,"SELECT USR_ID as ID FROM USERS WHERE CONCAT(USR_NAME , ' ' ,USR_SURNAME) = '" . $uso[$i] . "'"));
        $handle_stsk .= " ( " . $last . ", '" . $subject ."' , '" . $descript . "', " . $ui['ID'] . ", 2 , '" . $fechaF. "', " . $muser . " , " . $fac . ", NULL, 0, 1, 0, 'EX0000" . $ticket['TK'] . "', 2 , 1) ";
        $outcome .= $ui['ID'] . "|";
  
        if($i < count($uso)-1 ){
          $handle_stsk .= ", ";
        }

  }
  
    break;
}

if(!mysqli_query($datos, $handle_stsk)){
     echo $handle_stsk;
     echo mysqli_error($datos);

} else {


$uteam = mysqli_query($datos, "SELECT A.USR_ID, B.STSK_ID, B.STSK_ISS_ID FROM USERS A INNER JOIN SUBTASKS B ON(A.USR_ID = B.STSK_CHARGE_USR AND B.STSK_TICKET = 'EX0000" . $ticket['TK'] . "') WHERE (STSK_FAC_CODE = " . $fac . " AND STSK_TYPE= 0 AND STSK_MAIN_USR != STSK_CHARGE_USR AND STSK_MAIN_USR = " . $muser . ")");
 
    if($hdir = opendir("/var/www/html/" . $fac . "/_tmp/")) {

      while (false !== ($files = readdir($hdir))) {
  
        if(preg_match_all("/_\[" . $keyfile . "\]_/", $files) == 1){

          $extension = pathinfo($files, PATHINFO_EXTENSION);   

              while($uteams = mysqli_fetch_row($uteam)){

                if(copy("/var/www/html/" . $fac . "/_tmp/" . $files ,  "/var/www/html/" . $fac . "/" . $uteams[0] . "/" . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_" . $uteams[2] . "_." . $extension)){
                        //$echo_files .=  $dir . $uteams[0] . "_alt/" . basename(str_replace("_[" . $keyfile . "]_" , "", $files), "." . strtolower($extension)) . "_[" . $uteams[1] . "]_." . $extension; 
                  } 
              }


              mysqli_data_seek($uteam, 0);
        }
        if(strlen($files) > 4){
             unlink("/var/www/html/" . $fac . "/_tmp/" . $files);
        }
      
      }

    }
    
    closedir($hdir);

 echo (int)$number . "|" . $outcome . "|EX0000" . $ticket['TK'] . "|" . $last;


}

?>