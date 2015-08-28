<?
$val      = $_GET['val'];
$id       = $_GET['stsk_id'];
$iss_id   = $_GET['iss_id'];
$user     = $_GET['user'];
$muser    = $_GET['muser'];
$subject  = $_GET['subject'];
$descript = $_GET['des'];
$date     = $_GET['date'];
$fac      = $_GET['fac'];
$argument = $_GET['argument']; // 1 para internos 0 para externo
$ticket   = $_GET['ticket'];


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

// 1.) marca el progreso 
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS =  " . $val . " WHERE STSK_ID = " . $id . " ;");

if($argument == 0){ // si es externo 

//2.) get average
$avg = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(IFNULL(STSK_PROGRESS, 0))) AS VX FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR <> STSK_MAIN_USR AND STSK_TYPE = 0);"));
//3.) set the avg of the total progress in admin and issue
       mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS =  " . (int)$avg['VX'] . " WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 0);");
     
       mysqli_query($datos, "UPDATE ISSUES SET ISS_PROGRESS = " . (int)$avg['VX'] . " WHERE (ISS_ID = " . $iss_id . " AND ISS_FAC_CODE = " . $fac . ")");

    // 4.) update states local and total of subtask
      if((int)$val == 100){
        mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE STSK_ID = " . $id );
      }
      if((int)$avg['VX'] > 99.5){
        mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE (STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_MAIN_USR = " . $muser . ")");
      }
    
      // 6.) updating the issue state

      $iss_avg = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(IFNULL(STSK_PROGRESS, 0))) AS IAV FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_MAIN_USR <> STSK_CHARGE_USR AND STSK_TYPE = 0);"));
    
                 if((int)$iss_avg['IAV'] > 99.9){

                         mysqli_query($datos, "UPDATE ISSUES SET ISS_STATE = 5 WHERE (ISS_ID = " . $iss_id . " AND ISS_FAC_CODE = " . $fac . ")");

                 }
//7.) adding traffic

                $var1 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM `SUBTASKS` WHERE (STSK_ID = " . $id . " AND STSK_TYPE = 0)"));
                $var2 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM `SUBTASKS` WHERE (STSK_ISS_ID = " . $var1['STSK_ISS_ID'] . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 0)"));

                $insertar = "INSERT INTO `TRAFFIC` (TRF_STSK_ID, TRF_STSK_SRC_ID, TRF_DESCRIPT, TRF_SUBJECT, TRF_FAC_CODE, TRF_ING_DATE, TRF_USER) ";
                $insertar .= "VALUES (" . $id . ", " . $var2['STSK_ID'] . " , '" . $descript . "', '" . $subject . "', " . $fac . ", '" . $date . "', '" . $user . "');";
     
                      if(!mysqli_query($datos, $insertar)){
                      	 echo mysqli_error($datos);
                      } else {
                      	echo 1;
                      }

 
} else if($argument == 1) { // si es interno 

// 2.) get the origin user..

$min = mysqli_fetch_assoc(mysqli_query($datos, "SELECT MIN(STSK_ID) AS MIN FROM SUBTASKS WHERE (STSK_TICKET = '"  . $ticket . "' AND STSK_FAC_CODE = " . $fac . ") "));	
// 2.) get the average.. this time is special 
$avg = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(IFNULL(STSK_PROGRESS, 0))) AS VX FROM SUBTASKS WHERE (STSK_TICKET  = '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . " AND STSK_MAIN_USR = " . $muser . " AND STSK_CHARGE_USR <> STSK_MAIN_USR );"));

// update first and second states .. ask if there is a third state to update
       mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $avg['VX'] . " WHERE (STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_MAIN_USR = " . $muser . ")");

      if((int)$val == 100){
        mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE STSK_ID = " . $id );
      }
      if((int)$avg['VX'] > 99.5){
        mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE (STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_MAIN_USR = " . $muser . ")");
      }

      $test = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_RANGE FROM USERS A INNER JOIN SUBTASKS B ON(B.STSK_CHARGE_USR = A.USR_ID ) WHERE STSK_ID = " . $min['MIN'] ));
      
           if($test['USR_RANGE'] == 'sadmin'){

              // 4.) if this user the lucky guy?.. go ahead is he is the one!
           	  $navg = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(IFNULL(A.STSK_PROGRESS, 0))) AS VX FROM SUBTASKS A INNER JOIN USERS B ON(B.USR_ID = A.STSK_CHARGE_USR) WHERE (USR_RANGE = 'admin' AND STSK_TICKET  = '" . $ticket . "' AND STSK_FAC_CODE = " . $fac . " AND STSK_ID <> " . $min['MIN']. " );"));
           	             	           // 5.a) let he/she know inserting data into pseudo table
           	          mysqli_query($datos, "INSERT INTO PSEUDO (PSD_USR, PSD_TICKET, PSD_FAC_CODE, PSD_PERCENT) VALUES ( " . $muser . " ,'" . $ticket .  "', " . $fac . ", " . $avg['VX'] . " )");
                            
                      mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $navg['VX'] . " WHERE STSK_ID =" . $min['MIN'] );

           	          if($navg['VX'] > 99.5){

           	          	     mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE STSK_ID = " . $min['MIN']);
           	          }
         
           }

            $var1 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM `SUBTASKS` WHERE (STSK_ID = " . $id . " AND STSK_TYPE = 1)"));
            $var2 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM `SUBTASKS` A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID) WHERE (STSK_ISS_ID = " . $var1['STSK_ISS_ID'] ." AND B.USR_RANGE = 'admin' AND STSK_TYPE = 1)"));

            $insertar = "INSERT INTO `TRAFFIC_II` (TII_STSK_ID, TII_STSK_SRC_ID, TII_DESCRIPT, TII_SUBJECT, TII_FAC_CODE, TII_ING_DATE, TII_USER) ";
            $insertar .= "VALUES (" . $id . ", " . $var2['STSK_ID'] . " , '" . $descript . "', '" . $subject . "', " . $fac . ", '" . $date . "', '" . $user . "');";

                       if(!mysqli_query($datos, $insertar)){

                      	 echo mysqli_error($datos);

                          } else {

                          	echo 1;

                          }
}

?>