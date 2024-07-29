<?php
ob_start();
session_start();

if(!isset($_SESSION['nombre']))
{
    echo "Ingrese al Sistema Para Visualizar Este Reporte";
}
else
{
    if($_SESSION['almacen']==1)
    {
        require('PDF_MC_Table.php');
    }
    else
    {
        echo "No Tiene Permisos Para Visualizar Este Reporte";
    }
}
ob_end_flush();  
?>