<?php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

while(true){

// File Listener 
$user = $_GET['muser'];
$fac  = $_GET['fac'];

//set the dir
$rdir = "/var/www/html/" . $fac . "/" . $user ;

//get the folder... get the newest.. WTF

$array = array();
$f_array = array();
$files = scandir($rdir);

$i = 0;

foreach (scandir($rdir) as $node) {

    $nodePath = $folder . DIRECTORY_SEPARATOR . $node;
    if (is_dir($nodePath)) continue;

    $array[$i] = filemtime($rdir . $nodePath);
    $f_array[$i] = $nodePath;
    
    $i = $i +1;
}

$max = array_keys($array, max($array));
$newest_file = $f_array[$max];

//find the _[ISS number]_ in the last file
$matches = array();
preg_match('/_\[([0-9]+)\]_/', $newest_file , $matches);

//get the real number
$rx = array();
preg_match('/([0-9]+)/', $matches[0] , $rx);

//get all files with this 
$outcome =  preg_grep('/_\[' . $rx[0] . '\]_/', $files);

echo  implode("|", $outcome) . "|" . $rx[0] . "\n\n";

    ob_end_flush();     // Strange behaviour, will not work
    flush();            // Unless both are called !

sleep(2);

}


?>
