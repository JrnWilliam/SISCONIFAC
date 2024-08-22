<?php
require "../config/config.php";
include('../modelos/CRestaurar.php');

if(isset($_FILES['script']))
{
    $route = $_FILES['script']['tmp_name'];
    
    if(RestaurarDB(DBHOST, DB_USERNAME, DB_PASSWORD,$route))
    {
        echo "Restauración Completada con Éxito.";
    }
    else
    {
        echo "Error Durante la Restauración.";
    }
}
else
{
    echo "No ha Subido Ningún Archivo.";
}
?>