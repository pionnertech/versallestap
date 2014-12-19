<?php
$fac = $_REQUEST['fac'];
$code = $_REQUEST['code'];
$target_dir = "/var/www/html/images/". $fac . "/";
$target_file = $target_dir . basename($_FILES["upload"]["name"]);
$uploadOk = 1;

// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif','zip', 'docx', 'xls', 'pdf', 'doc','ppt' );

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}

	if(move_uploaded_file($_FILES['upl']['tmp_name'], 'uploads/'.$_FILES['upl']['name'])){
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;

?>