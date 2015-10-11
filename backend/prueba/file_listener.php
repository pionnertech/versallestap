<?php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

// File Listener 
$user = $_GET['muser'];
$fac  = $_GET['fac'];

$rdir = "/var/www/html/" . $fac . "/" . $user ;

while(true){

$fi = new FilesystemIterator($rdir, FilesystemIterator::SKIP_DOTS);

echo iterator_count($fi) . "\n\n";
	
}


flush();



?>
