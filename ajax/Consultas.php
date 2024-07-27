<?php
require_once '../modelos/CConsultas.php';

$consultas = new CConsultas();

switch($_GET["Operacion"])
{
    case 'FiltroXFecha':
        $finicio = $_REQUEST["f_inicio"];
        $ffinal = $_REQUEST["f_fin"];

        $respuesta = $consultas->Compras_x_Fecha($finicio,$ffinal);

        $data = Array();

        while($registro = $respuesta->fetch_object())
        {
            $data[] = array(
                "0"=>$registro->fecha,
                "1"=>$registro->usuario,
                "2"=>$registro->proveedor,
                "3"=>$registro->tipo_comprobante,
                "4"=>$registro->serie_comprobante.'-'.$registro->num_comprobante,
                "5"=>$registro->total_compra,
                "6"=>$registro->impuesto,
                "7"=>($registro->estado == 'Aceptado')?'<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Cerrado</span>'
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
    case 'FiltroXFechaCliente':
        $finicio = $_REQUEST["f_inicio"];
        $ffinal = $_REQUEST["f_fin"];
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";

        $respuesta = $consultas -> Ventas_x_Fecha_Cliente($finicio,$ffinal,$id);

        $data = Array();

        while($registro = $respuesta->fetch_object())
        {
            $data[] = array(
                "0"=>$registro->fecha,
                "1"=>$registro->usuario,
                "3"=>$registro->cliente,
                "3"=>$registro->tipo_comprobante,
                "4"=>$registro->serie_comprobante.'-'.$registro->num_comprobante,
                "5"=>$registro->total_venta,
                "6"=>$registro->impuesto,
                "7"=>($registro->estado == 'Aceptado')?'<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Cerrado</span>'
            );
            $resultado = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data
            );
        }
        echo json_encode($resultado);
    break;
}
?>