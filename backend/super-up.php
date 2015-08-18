<?php
/**
 * upload.php
 *
 * Copyright 2013, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */
#!! IMPORTANT: 
#!! this file is just an example, it doesn't incorporate any security checks and 
#!! is not recommended to be used in production environment as it is. Be sure to 
#!! revise it and customize to your needs.
// Make sure file is not cached (as it happens for example on iOS devices)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$fac  = $_REQUEST['fac'];
$code = $_REQUEST['code'];
$user = $_REQUEST['user'];





/* 
// Support CORS
header("Access-Control-Allow-Origin: *");
// other CORS headers if any...
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	exit; // finish preflight CORS requests here
}
*/
// 5 minutes execution time
@set_time_limit(5 * 60);
// Uncomment this one to fake upload time
// usleep(5000);
// Settings


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


//averiguar el numero de subtask matriz
$hn        = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ISS_ID FROM SUBTASKS WHERE STSK_ID = " . $code));
$depa      = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_DEPT AS DEPA FROM USERS WHERE USR_ID = " . $user));

$boss      = mysqli_fetch_assoc(mysqli_query($datos, "SELECT USR_ID AS BOSS FROM USERS WHERE (USR_DEPT = '" . $depa['DEPA'] . "' AND USR_RANGE = 'admin')"));
$real_code = mysqli_fetch_assoc(mysqli_query($datos, "SELECT STSK_ID FROM SUBTASKS WHERE (STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_FAC_CODE = " . $fac ." AND STSK_MAIN_USR = " . $boss['BOSS'] . " AND STSK_ISS_ID = " . $hn['STSK_ISS_ID'] . " AND STSK_TYPE = 1)"));


$targetDir = "/var/www/html/" . $fac;


//$targetDir = 'uploads';
$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds
// Create target dir

if(!is_dir($targetDir . "/int_temp/")){
	mkdir($targetDir . "/int_temp/", 0775, true);
}


// Get a file name
if (isset($_REQUEST["name"])) {
	$fileName = str_replace(" ", "-", $_REQUEST["name"]);
} elseif (!empty($_FILES)) {
	$fileName = str_replace(" ", "-", $_FILES["upl"]["name"]);
} else {
	$fileName = uniqid("file_");
}

if(preg_match_all('/\_\[\d+\]\_/', $fileName)){
	$fileName = preg_replace('/\_\[\d+\]\_/', '', $fileName);
}


$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
$extension = pathinfo($filePath , PATHINFO_EXTENSION);


$allowed = array('png', 'jpg', 'gif','zip', 'docx', 'xls', 'xlsx', 'pdf', 'doc','ppt', 'pptx' );
	if(!in_array(strtolower($extension), $allowed)){
		echo '{"jsonrpc" : "2.0", "error" : {"code": 403, "message": "file not allowed."}, "id" : "id"}';
		exit;
	}

// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
// Remove old temp files	
if ($cleanupTargetDir) {
	if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
	}
	while (($file = readdir($dir)) !== false) {
		$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		// If temp file is current file proceed to the next
		if ($tmpfilePath == "{$filePath}.part") {
			continue;
		}
		// Remove temp file if it is older than the max age and is not the current file
		if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
			@unlink($tmpfilePath);
		}
	}
	closedir($dir);
}	
// Open temp file
if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
	die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}
if (!empty($_FILES)) {
	if ($_FILES["upl"]["error"] || !is_uploaded_file($_FILES["upl"]["tmp_name"])) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	}
	// Read binary input stream and append it to temp file
	if (!$in = @fopen($_FILES["upl"]["tmp_name"], "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
} else {	
	if (!$in = @fopen("php://input", "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
}
while ($buff = fread($in, 4096)) {
	fwrite($out, $buff);
}
@fclose($out);
@fclose($in);
// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
	// Strip the temp .part suffix off 
	rename("{$filePath}.part", $filePath);
	copy($filePath,   $targetDir . "/" . $boss['BOSS'] . "_alt/" . basename($_FILES['upl']['name'] , "." . strtolower($extension)) . "_[" . $real_code['STSK_ID'] . "]_" . $user . "." . strtolower($extension)  );
	unlink($filePath);
}


?>