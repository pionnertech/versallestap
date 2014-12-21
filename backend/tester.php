<?php

chmod("/var/www/html/", 0775);
mkdir("/var/www/html/10001/", 0775);

if(is_dir("/var/www/html/10001/")){
	echo 1;
} else {
	echo 2;
}
?>