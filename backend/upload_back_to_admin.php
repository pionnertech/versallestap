<?php

$fac  = $_REQUEST['fac'];
$code = $_REQUEST['code'];
$user = $_REQUEST['user'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


$target_dir = "/var/www/html/" . $fac . "/";
$target_file = $target_dir . basename($_FILES["upl"]["name"]);
$uploadOk = 1;


if(!is_dir($target_dir)){
	mkdir($target_dir, 0775, true);
}

if(!is_dir($target_dir . $user . "_in/")){
	chmod($target_dir . $user . "_in/");
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
	if(move_uploaded_file($_FILES['upl']['tmp_name'] , $target_dir . "/" . $user . "_in/" . basename($_FILES['upl']['name'] , "." . strtolower($extension)) . "_" . $code . "_" . $user . "." . strtolower($extension) )){
		echo '{"status":"success"}';
		
	}
}

echo '{"status":"error"}';
exit;

?>


