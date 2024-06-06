<?php
require_once "config.php";

$conexion = new mysqli(DBHOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

mysqli_query($conexion,'SET NAMES "'.DB_ENCODE.'"');

if (mysqli_connect_errno())
{
    printf("Fallo la Conexión a la Base de Datos: %s\n",mysqli_connect_errno());
    exit();
}

if (!function_exists('Ejecutar_Consulta'))
{
    function Ejecutar_Consulta($sql)
    {
        global $conexion;
        $query = $conexion->query($sql);
        return $query;
    }

    function EjecutarConsultaSimpleFila($sql)
    {
        global $conexion;
        $query = $conexion->query($sql);
    
        if (!$query) {
            // Manejo de errores, por ejemplo, lanzar una excepción o registrar el error
            die('Error en la consulta: ' . $conexion->error);
        }
    
        $row = $query->fetch_assoc();
        return $row;
    }

    function EjecutarConsultaRetornarID($sql)
    {
        global $conexion;
        $query = $conexion->query($sql);
        return $conexion->insert_id;
    }

    function LimpiarCadena($str)
    {
        global $conexion;
        $str = mysqli_real_escape_string($conexion,trim($str));
        return htmlspecialchars($str);
    }
}
?>