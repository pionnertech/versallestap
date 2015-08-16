<?php 
$val = $_GET['val']; // valor de progreso
$id = $_GET['stsk_id']; // stsk id 
$iss_id = $_GET['iss_id']; //  ??????????
$muser = $_GET['muser'];
$subject = $_GET['subject'];
$descript = $_GET['des'];
$date = $_GET['date'];
$fac = $_GET['fac'];
$user = $_GET['user'];
$ticket = $_GET['ticket'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
//origin
$min = mysqli_fetch_assoc(mysqli_query($datos, "SELECT MIN(STSK_ID) as MIN FROM SUBTASKS WHERE (STSK_FAC_CODE = " . $fac . " AND STSK_TICKET= '" . $ticket . "')"));
//range current User
$c_range = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_RANGE AS RAN FROM USERS WHERE USR_ID = " . $user));


//switch según el rango 
switch ($c_range['RAN']) {

	case 'back-user':
       // set own progress first
       mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $val . " WHERE (STSK_TYPE = 1 AND STSK_CHARGE_USR = " . $user . " AND  STSK_FAC_CODE = " . $fac . " AND STSK_ISS_ID = " . $iss_id . ");");

       //get the AVG and set it to the current admin 
       $avg = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(CASE WHEN STSK_PROGRESS IS NULL THEN 0 ELSE STSK_PROGRESS END)) AS AVX FROM SUBTASKS WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_CHARGE_USR <> " . $muser . " AND STSK_TICKET = '" . $ticket . "' AND STSK_FAC_CODE = " . $fac. ")"));
              mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $avg['AVX'] . ", STSK_ANCIENT_PRO = " . $avg['AVX'] . " WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_TYPE = 1 AND STSK_CHARGE_USR = " . $muser . ")");
              
              //detecta si el origen es el sadmin , de aho mandale aviso 
              $look = mysqli_fetch_assoc(mysqli_query($datos, "SELECT A.USR_RANGE AS RAN FROM USERS A INNER JOIN SUBTASKS B ON(A.USR_ID = B.STSK_CHARGE_USR) WHERE STSK_ID = " . $min['MIN']));
              if ($look['RAN'] == 'sadmin'){
              	  //update total progress
              	       $pro= mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(STSK_PROGRESS)) AS AVX FROM SUBTASKS A INNER JOIN USERS B ON(B.USR_ID = A.STSK_CHARGE_USR) WHERE (USR_RANGE = 'admin' AND STSK_TICKET = '" . $ticket . "' AND STSK_FAC_CODE = " . $fac .")"));
                                                mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $pro['AVX'] . " WHERE STSK_ID = " . $min['MIN'] );
                  //let him know that a upgrede was uploaded
                             mysqli_query($datos, "INSERT INTO PSEUDO (PSD_USR, PSD_TICKET, PSD_FAC_CODE, PSD_PERCENT) VALUES ( " . $muser . " ,'" . $ticket .  "', " . $fac . ", " . $avg['AVX']  . " )");  
                  // maybe you can change the state ....
                             if((int)$pro['AVX'] > 99.95){
                             	          mysqli_query($datos, "UPDATE SUBTAKS SET STSK_STATE= 5 WHERE STSK_ID = " . $min['MIN']);
                             }
              }

              //check level of finishiment of your own task
               if($val > 99.9){
         	                 mysqli_query($datos, "UPDATE SUBTASKS SET  STSK_STATE = 5 WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_FAC_CODE = " . $fac . " AND STSK_CHARGE_USR = " . $user . ")");
               }
               //check the level of your boss 
               if((int)$avg['AVX']  > 99.85){
               	             mysqli_query($datos, "UPDATE SUBTAKS SET STSK_STATE = 5 WHERE STSK_ID =" . $min['MIN']);
               }

               //record the traffic 
               $var1 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM `SUBTASKS` WHERE (STSK_TYPE = 1 AND STSK_TICKET = '" . $ticket . "' AND STSK_FAC_CODE =  " . $fac . " AND STSK_CHARGE_USR = " . $muser .")"));
               $var2 = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM `SUBTASKS` WHERE (STSK_ISS_ID = " . $var1['STSK_ISS_ID'] . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 1)"));

               $insertar = "INSERT INTO `TRAFFIC_II` (TII_STSK_ID, TII_STSK_SRC_ID, TII_DESCRIPT, TII_SUBJECT, TII_FAC_CODE, TII_ING_DATE, TII_USER) ";
               $insertar .= "VALUES (" . $id . ", " . $var2['STSK_ID'] . " , '" . $descript . "', '" . $subject . "', " . $fac . ", '" . $date . "', '" . $user . "');";

               if(!mysqli_query($datos, $insertar)){
	               echo mysqli_error($datos);
	                 exit;

               } else {

                 echo 1;

                }

		break;

	case 'admin':
	//set own pro first and confirm that you're the only responsable
     mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $val . " WHERE (STSK_TYPE = 1 AND STSK_CHARGE_USR = " . $user . " AND  STSK_FAC_CODE = " . $fac . " AND STSK_ISS_ID = " . $iss_id . ");");
     mysqli_query($datos, "UPDATE SUBTASKS SET STSK_RESP = 1 WHERE STSK_ID = " . $id);
    // get the AVG of all admins progress and set it to the sadmin
    $avg = mysqli_fetch_assoc(mysqli_query($datos, "SELECT ROUND(AVG(STSK_PROGRESS)) AS AVX FROM SUBTASKS A INNER JOIN USERS B ON(B.USR_ID = A.STSK_CHARGE_USR) WHERE (USR_RANGE = 'admin' AND STSK_TICKET = '" . $ticket . "' AND STSK_FAC_CODE = " . $fac .")"));
           mysqli_query($datos, "UPDATE SUBTASKS SET STSK_PROGRESS = " . $avg['AVX'] . " WHERE STSK_ID = " . $min['MIN'] );

          //check level of finishiment from your own task
         if($val > 99.9){
         	mysqli_query($datos, "UPDATE SUBTASKS SET  STSK_STATE = 5 WHERE (STSK_ISS_ID = " . $iss_id . " AND STSK_FAC_CODE = " . $fac . " AND STSK_CHARGE_USR = " . $user . ")");
         }

         if((int)$avg['AVX'] > 99.95 ){

           mysqli_query($datos, "UPDATE SUBTASKS SET STSK_STATE = 5 WHERE STSK_ID =" . $min['MIN']);
         }

               $insertar = "INSERT INTO `TRAFFIC_II` (TII_STSK_ID, TII_STSK_SRC_ID, TII_DESCRIPT, TII_SUBJECT, TII_FAC_CODE, TII_ING_DATE, TII_USER) ";
               $insertar .= "VALUES (" . $id . ", " . $min['MIN'] . " , '" . $descript . "', '" . $subject . "', " . $fac . ", '" . $date . "', '" . $user . "');";

               if(!mysqli_query($datos, $insertar)){
	               echo mysqli_error($datos);
	                 exit;

               } else {

                 echo 1;

                }
          
		break;	
	default:
		# code...
		break;
}





?>



