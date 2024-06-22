<?php
require "../config/conexion.php";

class CPermiso
{
    public function __construct()
    {
        
    }

    public function MostrarPermisos()
    {
        $sql = "SELECT *FROM permiso";
        return Ejecutar_Consulta($sql);
    }
}
?>