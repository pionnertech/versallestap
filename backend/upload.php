<?php

$fac = $_REQUEST['fac'];
$code = $_REQUEST['code'];
$user = $_REQUEST['user'];


$target_dir = "/var/www/html/F" . $fac . "/";
$target_file = $target_dir . basename($_FILES["upl"]["name"]);
$uploadOk = 1;

if(!is_dir($target_dir)){
	mkdir($target_dir, 775);
}

if(!is_dir($target_dir . $user . "/")){
	mkdir($target_dir . $user . "/", 775);
}

// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif','zip', 'docx', 'xls', 'pdf', 'doc','ppt' );

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}

	if(move_uploaded_file($_FILES['upl']['tmp_name'] , $target_dir . "/" . $user . "/" . $_FILES['upl']['name'])){
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;

?>