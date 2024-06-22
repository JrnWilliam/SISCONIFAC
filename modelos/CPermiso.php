<?php
require "../config/conexion.php";

class CPermiso
{
    public function __construct()
    {
        
    }

    public function ListarPermisos()
    {
        $sql = "SELECT *FROM permiso";
        return Ejecutar_Consulta($sql);
    }
}
?>