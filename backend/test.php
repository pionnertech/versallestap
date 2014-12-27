
<?php
$directorio = opendir("../10000/."); //ruta actual
while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
    if (is_dir($archivo))//verificamos si es o no un directorio

    {
       while($subfolder = readdir($archvo)){
       	      echo  $subfolder;

        } //de ser un directorio lo envolvemos entre corchetes
    }
    else
    {
        echo $archivo . "<br />";
    }
}
?>
