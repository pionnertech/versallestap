<?php 

 $rest = shell_exec("netstat -t");
  

 preg_match_all('/\b\d{1,3}\-\d{1,3}\-\d{1,3}\-\d{1,3}\b/g', $rest , $match_array)

print_r($match_array);

?>