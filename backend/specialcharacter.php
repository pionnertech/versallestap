<?php

 header('Access-Control-Allow-Origin: http://cumplimos.cl');  

 $datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

    $query = "SELECT * FROM  USERS";

       $handler = mysqli_query($datos, $query);

       while($han = mysqli_fetch_row($handler)){
        
          echo $han[0] . "/" . $han[1] . "/" . $han[2];

       }


