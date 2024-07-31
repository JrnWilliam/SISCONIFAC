<?php
    ob_start();
    session_start();

    if(!isset($_SESSION["nombre"]))
    {
        echo "Ingrese al Sistema Para Poder Generar Esta Factura";
    }
    else
    {
        if($_SESSION['compras']==1)
        {
            require 'MFactura.php';
        }
        else
        {
            echo "No Tiene Permisos Para Exportar Esta Factura";
        }
    }
    ob_end_flush();
?>