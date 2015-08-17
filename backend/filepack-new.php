 <?php 

$ticket = $_GET['ticket'];
$fac    = $_GET['fac'];
$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
$team = "";

$filestr    = "";
$setClass  = "";
$cor       = "";
//discrimination


$it = mysqli_query($datos, "SELECT A.STSK_ID, B.USR_ID, B.USR_RANGE FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID ) WHERE (STSK_TYPE = 1  AND STSK_TICKET= '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . " AND USR_RANGE ='back-user' )");
$ad = mysqli_query($datos, "SELECT A.STSK_ID, B.USR_ID, B.USR_RANGE, A.STSK_RESP FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID ) WHERE (STSK_TYPE = 1 AND USR_RANGE = 'admin' AND STSK_TICKET= '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . ")");


while($fila = mysqli_fetch_row($ad)){

    $handle = opendir("/var/www/html/" . $fac . "/" . $fila[1] . "_alt/");

        while (false !== ($file = readdir($handle))){

        	   while ($as = mysqli_fetch_row($it)){
                         
	                     if( preg_match_all("/_\[" . $as[0] . "\]_/", $file) == 1){


          $extension = pathinfo($file, PATHINFO_EXTENSION);

              switch($extension){
                case "pdf": 
            $setClass = "pdf-o";
            $cor = "#FA2E2E";    
        break;
                case "lsx":
            $setClass = "excel-o";
            $cor = "#44D933";
        break;
                case "ocx":
            $setClass = "word-o"; 
            $cor = "#5F6FE0";
        break;
                case "doc":
            $setClass = "word-o"; 
            $cor = "#5F6FE0";
        break;
                case "xls":
            $setClass = "excel-o";
            $cor = "#44D933";
        break;
                case "zip":
            $setClass = "zip-o";
            $cor = "#DDCE62";
        break;
                case "png" : 
            $setClass = "picture-o";
            $cor = "#338B93";
        break; 
                case "jpg" : 
            $setClass = "picture-o";
            $cor = "#338B93";
        break; 
                case "gif" : 
            $setClass = "picture-o";
            $cor = "#338B93";
        break; 
                case "bmp" : 
            $setClass = "picture-o";
            $cor = "#338B93";
        break;
                case "ptx" : 
            $setClass = "powerpoint-o";
            $cor = "#A80B9C";
        break;

    }
?>
 <a href="../<? echo $fac ?>/<? echo $fila[1] ?>_alt/<? echo $file  ?>"  download>
  <p style='display: inline-block' title="<?  echo preg_replace('/\](.*?)\./', "]_" . $fila[1]  . ".", $file)  ?>"></p>
  <i class="fa fa-file-<?  echo $setClass ?> fa-2x" style="color:<? echo  $cor  ?> ; margin: 0 0.4em"></i>
</a> 
<?
	                        }
        	          }

                mysqli_data_seek($it, 0);
             }
             closedir($handle);
}



 mysqli_data_seek($it, 0);
 mysqli_data_seek($ad, 0);

$reach = mysqli_query($datos, "SELECT A.STSK_ID, B.USR_ID, B.USR_RANGE FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID ) WHERE (STSK_TYPE = 1 AND USR_RANGE = 'admin' AND STSK_TICKET= '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . " AND STSK_RESP = 1)");

 while($as = mysqli_fetch_row($reach)){

$handle = opendir("/var/www/html/" . $fac . "/1_alt/");

        while (false !== ($file = readdir($handle))){

              // echo $file . " --- " . preg_match_all("/_\[" . $as[0] . "\]_/", $file) . " --- " . "preg_match_all(/_\[" . $as[0] . "\]_/," . $file . ") <br />";
	                     
                       if( preg_match_all("/_\[" . $as[0] . "\]_/", $file) == 1){

           $extension = pathinfo($file, PATHINFO_EXTENSION);

              switch($extension){
                case "pdf": 
            $setClass = "pdf-o";
            $cor = "#FA2E2E";    
        break;
                case "lsx":
            $setClass = "excel-o";
            $cor = "#44D933";
        break;
                case "ocx":
            $setClass = "word-o"; 
            $cor = "#5F6FE0";
        break;
                case "doc":
            $setClass = "word-o"; 
            $cor = "#5F6FE0";
        break;
                case "xls":
            $setClass = "excel-o";
            $cor = "#44D933";
        break;
                case "zip":
            $setClass = "zip-o";
            $cor = "#DDCE62";
        break;
                case "png" : 
            $setClass = "picture-o";
            $cor = "#338B93";
        break; 
                case "jpg" : 
            $setClass = "picture-o";
            $cor = "#338B93";
        break; 
                case "gif" : 
            $setClass = "picture-o";
            $cor = "#338B93";
        break; 
                case "bmp" : 
            $setClass = "picture-o";
            $cor = "#338B93";
        break;
                case "ptx" : 
            $setClass = "powerpoint-o";
            $cor = "#A80B9C";
        break;

    }


    echo  '<a href="../' . $fac .'/' . $as[1] . '_alt/' . $file . '"  download><p style="display: inline-block" title="' . $as[1] .  '"></p><i class="fa fa-file-' . $setClass . ' fa-2x" style="color:' . $cor.  '; margin: 0 0.4em"></i></a>'; 

	                        }
        	          
                   }

    closedir($handle);
           }



 ?>
