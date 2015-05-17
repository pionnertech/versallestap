<?php

$fac  = $_REQUEST['fac'];
$code = $_REQUEST['code'];
$user = $_REQUEST['user'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


//averiguar el numero de subtask matriz
$hn = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM SUBTASKS WHERE STSK_ID = " . $code));
$real_code = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM SUBTASKS WHERE (STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_ISS_ID = " . $hn['STSK_ISS_ID'] . " AND STSK_TYPE = 0)"));

$target_dir = "/var/www/html/" . $fac . "/";
$target_file = $target_dir . basename($_FILES["upl"]["name"]);
$uploadOk = 1;


if(!is_dir($target_dir)){
	mkdir($target_dir, 0775, true);
}

if(!is_dir($target_dir . $user . "_in/")){

	chmod($target_dir . $user . "_in/", 0775);
	mkdir($target_dir . $user . "_in/", 0775, true);
}

// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif','zip', 'docx', 'xls', 'xlsx', 'pdf', 'doc','ppt' );

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}

	if(move_uploaded_file($_FILES['upl']['tmp_name'] , $target_dir . "/" . $user . "_in/" . basename($_FILES['upl']['name'] , "." . strtolower($extension)) . "_" . $real_code['STSK_ID'] . "_" . $user . "." . strtolower($extension) )){
		echo '{"status":"success"}';
		
	}
}

echo '{"status":"error"}';
exit;

?>


