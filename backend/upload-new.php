<?php

$fac  = $_REQUEST['fac'];
$code = $_REQUEST['code'];
$user = $_REQUEST['user'];
$iss  = $_REQUEST['issId'];
$muser = $_REQUEST['muser'];

$target_dir = "/var/www/html/" . $fac . "/";
$target_file = $target_dir . basename($_FILES["upl"]["name"]);
$uploadOk = 1;
echo "executing";

if($user == "Mi Departamento"){

$dept = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_DEPT FROM USERS WHERE (USR_ID = " . $muser . " AND USR_FACILITY= " . $fac . ")"));

$users = mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE (USR_DEPT = " . $dept['USR_DEPT'] . " AND USR_FACILITY = " . $fac . " AND USR_RANGE = 'back-user')");

       while($fila = mysqli_fetch_row($users)){

             $allowed = array('png', 'jpg', 'gif','zip', 'docx', 'xls', 'xlsx', 'pdf', 'doc','ppt', 'pptx' );

             if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	               $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	                        if(!in_array(strtolower($extension), $allowed)){
	                        	echo '{"status":"error"}';
	                        	
	                        }

	                        if(copy($_FILES['upl']['tmp_name'] , $target_dir . $fila[0] . "/" . basename($_FILES['upl']['name'] , "." . strtolower($extension)) . "_" . $iss . "_" . $fila[0] . "." . strtolower($extension) )){
	                        	echo '{"status":"success"}';
	                        	
	                        }
                }
                     
            }


} else {

echo "se está ejecutando";
	$team = explode(",", $user);

	for($i=0; $i < count($team); $i++){
    echo strtoupper($team[$i]);
		 $usr_id_q = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_ID FROM USERS WHERE (CONCAT(USR_NAME, ' ' , USR_SURNAME) = '" . strtoupper($team[$i]) . "' AND USR_FACILITY = " . $fac .")"));

            if(!is_dir($target_dir . $usr_id_q['USR_ID'] . "/")){
	                  chmod($target_dir . $usr_id_q['USR_ID'] . "/");
	                  mkdir($target_dir . $usr_id_q['USR_ID'] . "/", 0775, true);
                    }

		    $allowed = array('png', 'jpg', 'gif','zip', 'docx', 'xls', 'xlsx', 'pdf', 'doc','ppt', 'pptx' );

             if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	       $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
           echo  $target_dir . $usr_id_q['USR_ID'] . "/" . basename($_FILES['upl']['name'] , "." . strtolower($extension)) . "_" . $iss . "_" . $usr_id_q['USR_ID']  . "." . strtolower($extension) ;
	                        if(!in_array(strtolower($extension), $allowed)){
	                        	echo '{"status":"error"}';
	                        	
	                        }
	                        if(copy($_FILES['upl']['tmp_name'] , $target_dir . $usr_id_q['USR_ID'] . "/" . basename($_FILES['upl']['name'] , "." . strtolower($extension)) . "_" . $iss . "_" . $usr_id_q['USR_ID']  . "." . strtolower($extension) )){
	                        	echo '{"status":"success"}';
	                        	
	                        }
                }
	    }
}

echo '{"status":"error"}';
exit;

?>