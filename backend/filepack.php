 <?php 

$ticket = $_GET['ticket'];
$fac    = $_GET['fac'];
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$it = mysqli_query($datos, "SELECT A.STSK_ID, B.USR_ID, B.USR_RANGE FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID ) WHERE (STSK_TYPE = 1  AND STSK_TICKET= '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . " AND USR_RANGE ='back-user' )");
$ad = mysqli_query($datos, "SELECT A.STSK_ID, B.USR_ID, B.USR_RANGE FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID ) WHERE (STSK_TYPE = 1 AND STSK_CHARGE_USR = 'admin' AND STSK_TICKET= '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . " )");



while($fila = mysqli_fetch_row($ad)){

    $handle = opendir("/var/www/html/" . $fac . "/" . $fila[1] . "_alt/");

        while (false !== ($file = readdir($handle))){

        	   while ($as = mysqli_fetch_row($it)){
                        // echo $file . "  - -- " . preg_match_all("/_\[" . $as[0] . "\]_/", $file) . " --- " . "preg_match_all(/_\[" . $as[0] . "\]_/," . $file . ")";
	                     if( preg_match_all("/_\[" . $as[0] . "\]_/", $file) == 1){
                              echo $file . ",";
	                        }
        	          }
                mysqli_data_seek($it, 0);
             }
             closedir($handle);
}

 mysqli_data_seek($it, 0);
 mysqli_data_seek($ad, 0);

    $handle = opendir("/var/www/html/" . $fac . "/1_alt/");
 while ($as = mysqli_fetch_row($ad)){
        while (false !== ($file = readdir($handle))){
                        // echo $file . "  - -- " . preg_match_all("/_\[" . $as[0] . "\]_/", $file) . " --- " . "preg_match_all(/_\[" . $as[0] . "\]_/," . $file . ")";
	                     if( preg_match_all("/_\[" . $as[0] . "\]_/", $file) == 1){
                              echo $file . ",";
	                        }
        	          
               
             }

           }


 mysqli_data_seek($it, 0);

 ?>
