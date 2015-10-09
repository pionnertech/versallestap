 <?php 

$ticket = $_GET['ticket'];
$fac    = $_GET['fac'];
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$it = mysqli_query($datos, "SELECT A.STSK_ID, B.USR_ID, B.USR_RANGE, A.STSK_ISS_ID FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID ) WHERE (STSK_TYPE = 0 AND STSK_TICKET= '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . " AND USR_RANGE ='back-user' )");
$ad = mysqli_query($datos, "SELECT A.STSK_ID, B.USR_ID, B.USR_RANGE, A.STSK_ISS_ID FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID ) WHERE (STSK_TYPE = 0 AND USR_RANGE = 'admin' AND STSK_TICKET= '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . ")");

while($fila = mysqli_fetch_row($ad)){
$handle = opendir("/var/www/html/" . $fac . "/" . $sadmin_get['USR_ID'] . "/");

        while (false !== ($file = readdir($handle))){

               //echo $file . " --- " . preg_match_all("/_\[" . $as[0] . "\]_/", $file) . " --- " . "preg_match_all(/_\[" . $as[0] . "\]_/," . $file . ") <br />";
                       
                       if( preg_match_all("/_\[" . $as[3] . "\]_/", $file) == 1){

                              echo $file . ",";

                          }
                    
                   }

    closedir($handle);
          } 







/*
$it = mysqli_query($datos, "SELECT A.STSK_ID, B.USR_ID, B.USR_RANGE, A.STSK_ISS_ID FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID ) WHERE (STSK_TYPE = 0 AND STSK_TICKET= '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . " AND USR_RANGE ='back-user' )");
$ad = mysqli_query($datos, "SELECT A.STSK_ID, B.USR_ID, B.USR_RANGE, A.STSK_ISS_ID FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID ) WHERE (STSK_TYPE = 0 AND USR_RANGE = 'admin' AND STSK_TICKET= '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . ")");




//echo $fila[1];

    $handle = opendir("/var/www/html/" . $fac . "/" . $fila[1] . "/");

        while (false !== ($file = readdir($handle))){

        	   while ($as = mysqli_fetch_row($it)){
                        //echo $file . "  - -- " . preg_match_all("/_\[" . $as[0] . "\]_/", $file) . " --- " . "preg_match_all(/_\[" . $as[0] . "\]_/," . $file . ")";
	                     if( preg_match_all("/_\[" . $as[3] . "\]_/", $file) == 1){
                              echo $file . ",";
	                        }
        	          }
                mysqli_data_seek($it, 0);
             }
             closedir($handle);
}

echo "|";

 mysqli_data_seek($it, 0);
 mysqli_data_seek($ad, 0);

$sadmin_get = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE ( USR_RANGE = 'sadmin' AND USR_FACILITY = " . $fac . ")"));
$reach      = mysqli_query($datos, "SELECT A.STSK_ID, B.USR_ID, B.USR_RANGE, A.STSK_ISS_ID FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID ) WHERE (STSK_TYPE = 0 AND USR_RANGE = 'admin' AND STSK_TICKET= '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . " AND STSK_RESP = 1)");

 while($as = mysqli_fetch_row($reach)){


$handle = opendir("/var/www/html/" . $fac . "/" . $sadmin_get['USR_ID'] . "/");

        while (false !== ($file = readdir($handle))){

               //echo $file . " --- " . preg_match_all("/_\[" . $as[0] . "\]_/", $file) . " --- " . "preg_match_all(/_\[" . $as[0] . "\]_/," . $file . ") <br />";
	                     
                       if( preg_match_all("/_\[" . $as[3] . "\]_/", $file) == 1){

                              echo $file . ",";

	                        }
        	          
                   }

    closedir($handle);
           }

*/
 ?>
