<?php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

while(true){

// File Listener 
$user = $_GET['muser'];
$fac  = $_GET['fac'];

$rdir = "/var/www/html/" . $fac . "/" . $user ;

$fi = new FilesystemIterator($rdir, FilesystemIterator::SKIP_DOTS);

echo iterator_count($fi) . "\n\n";

    ob_end_flush();     // Strange behaviour, will not work
    flush();            // Unless both are called !

sleep(1);

}


?>
