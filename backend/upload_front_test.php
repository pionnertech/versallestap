<?php

$counter = count($_FILES['file']['tmp-name']);

for ($i = 0 ; $i < $counter ; $i++){

 echo $_FILES['file']['tmp-name'][$i];
}



?>