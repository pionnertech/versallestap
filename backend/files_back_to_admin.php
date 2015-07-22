<?php
// esto es desde un back
//backend/files_back_to_admin.php?fac=10000&user=119&stsk=12&kind=1&current=118 cuando aux_stsk == 0


//esto es desde un admin o sadmin cuando escucha progreso
//backend/files_back_to_admin.php?fac=10000&user=1&stsk=16&kind=1&current=1 cuando aux_stsk !== 0

//esto es lo que se manda dee un admin cuando le llega un request de otro admin 
//backend/files_back_to_admin.php?fac=10000&user=1&stsk=16&kind=1


//cuando viende de un progreso de un back hacia admin externo
//backend/files_back_to_admin.php?fac=10000&user=2&stsk=44&kind=0&current=2 cuando aux_stsk == 0
$fac     = $_REQUEST['fac'];
$user    = $_REQUEST['user'];
$stsk    = $_REQUEST['stsk'];
$kind    = $_REQUEST['kind'];
$current = $_REQUEST['current'];
$first   = $_REQUEST['first'];
$ticket  = $_REQUEST['ticket'];
$bingo   = false;

$factor = 0;
if($first == 1){
  exit;
}

if($current !== $user && isset($current)){
  $bingo = true;
}


$datos = $datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$query =  mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM SUBTASKS WHERE STSK_ID = " . $stsk ));

$userId = mysqli_query($datos, "SELECT A.STSK_CHARGE_USR, A.STSK_ID, B.USR_RANGE FROM SUBTASKS A INNER JOIN USERS B ON(B.USR_ID = A.STSK_CHARGE_USR) WHERE (STSK_TICKET = '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . " AND STSK_TYPE= " . $kind .");");

if($kind == 0 || $kind == "0"){
 while( $fila = mysqli_fetch_row($userId) ){
   $rdir = "/var/www/html/" . $fac . "/" . $fila[0] . "_in/";
    if(!is_dir($rdir)) {
        mkdir($rdir, 0775, true);
     }
   if($hdir = opendir($rdir)){
     while (false !== ($files = readdir($hdir))){
//echo "// " . $files . " // " . preg_match_all("/_" . $stsk . "_/", $files) . " [ " . $fila[0] . "]" . "<br />";
         if(preg_match_all("/_" . $stsk  . "_/", $files) == 1){
            $outcome .= "../". $fac . "/" . $fila[0] ."_in/" . $files . "|";
        }
    }
  }
  closedir($hdir);
}
} else {

while( $fila = mysqli_fetch_row($userId) ){
  $usInt = mysqli_query($datos, "SELECT STSK_ID FROM SUBTASKS WHERE (STSK_TICKET = '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . ")");
  while($kilo = mysqli_fetch_row($usInt) ){
   $rdir = "/var/www/html/" . $fac . "/" . $fila[0] . "_alt/";
       if(!is_dir($rdir)) {
            mkdir($rdir, 0775, true);
          }
   if($hdir = opendir($rdir)){
     while (false !== ($files = readdir($hdir))){

         if(preg_match_all("/_\[" . $kilo[0]  . "\]_/", $files) == 1){

         if($bingo == true){

            $outcome .= "../". $fac . "/" . $fila[0] ."_alt/" . $files . "|";
          } else {

              if((int)$user == (int)$fila[0] ){
                $outcome .= "../". $fac . "/" . $fila[0] ."_alt/" . $files . "|";
            } else {
              continue;
         }
        }
         }
      }
     }
   closedir($hdir);
  }
 }
}
echo $outcome;
?>