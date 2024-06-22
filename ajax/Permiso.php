<?php
require_once "../modelos/CPermiso.php";

$permiso = new CPermiso();

switch($_GET["operacion"])
{
    case 'MostrarPermisos':
        $respuesta = $permiso->MostrarPermisos();
        $data = Array();

        while($registro = $respuesta->fetch_object())
        {
            $data = array(
                "0" => $registro->nombre
            );
        }
        $resultado = array(
            "sEcho"=>1,
            "iTotalRecords"=>count($data),
            "iTotalDisplayRecords"=>count($data),
            "aaData"=>$data
        );
        echo json_encode($resultado);
    break;
}

?>