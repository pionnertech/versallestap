<?php


mkdir("/var/www/html/10000/", 755);

if(is_dir("/var/www/html/10000/")){
	echo 1;
} else {
	echo 2;
}
?>