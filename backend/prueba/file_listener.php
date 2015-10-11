<?php

//header('Content-Type: text/event-stream');
//header('Cache-Control: no-cache');

// File Listener 
$user = $_GET['muser'];
$fac  = $_GET['fac'];

$rdir = "/var/www/html/" . $fac . "/" . $user ;

$fi = new FilesystemIterator($rdir, FilesystemIterator::SKIP_DOTS);
printf("There were %d Files", iterator_count($fi)) . "<br />";

echo shell_exec($rdir . " find . -type f | wc -l");


?>
