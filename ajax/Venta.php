<?php
session_start();

require_once '../modelos/CVentas.php';

$ventas = new CVentas();

$idventa = isset($_POST["idventa"])?LimpiarCadena($_POST["idventa"]):"";
$idcliente = isset($_POST["idcliente"])?LimpiarCadena($_POST["idcliente"]):"";
$idusuario = $_SESSION["idusuario"];
$tipocomprobante = isset($_POST["tipocomprobante"])?LimpiarCadena($_POST["tipocomprobante"]):"";
$seriecomprobante = isset($_POST["seriecomprobante"])?LimpiarCadena($_POST["seriecomprobante"]):"";
$numcomprobante = isset($_POST["numcomprobante"])?LimpiarCadena($_POST["numcomprobante"]):"";
$fechahora = isset($_POST["fechahora"])?LimpiarCadena($_POST["fechahora"]):"";
$impuesto = isset($_POST["impuesto"])?LimpiarCadena($_POST["impuesto"]):"";
$totalventa = isset($_POST["totalventa"])?LimpiarCadena($_POST["totalventa"]):"";

switch($_GET["Operacion"])
{
    case 'GuardarEditar':
        if(empty($idventa))
        {
            $respuesta = $ventas->InsertarVentas($idcliente,$idusuario,$tipocomprobante,$seriecomprobante,$numcomprobante,$fechahora,$impuesto,$totalventa,isset($_POST["idarticulo"])?$_POST["idarticulo"]:[],isset($_POST["cantidad"])?$_POST["cantidad"]:[],isset($_POST["precioventa"])?$_POST["precioventa"]:[],isset($_POST["descuento"])?$_POST["descuento"]:[]);
            echo $respuesta ? "La Venta se Registro Correctamente" : "Error, No se Logro Registrar la Venta";
        }
        else
        {
            $respuesta = $ventas->EditarVentas($idventa,$idcliente,$idusuario,$tipocomprobante,$seriecomprobante,$numcomprobante,$fechahora,$impuesto,$totalventa,isset($_POST["idarticulo"])?$_POST["idarticulo"]:[],isset($_POST["cantidad"])?$_POST["cantidad"]:[],isset($_POST["precioventa"])?$_POST["precioventa"]:[],isset($_POST["descuento"])?$_POST["descuento"]:[]);
            echo $respuesta ? "Se Edito Correctamente la Venta" : "Error, No se Logro Editar la Venta";
        }
    break;
    case "AnularVenta":
        $respuesta = $ventas->AnularVentas($idventa);
        echo $respuesta ? "Se Anulo Correctamente la Venta" : "Error no se Logro Anular la Venta";
    break;
    case "SeleccionarRegistroVentas":
        $respuesta = $ventas->SeleccionarRegistroVentas($idventa);
        echo json_encode($respuesta);
    break;
    case "MostrarVentas":
        $respuesta = $ventas->MostrarVentas();
        $data = Array();

        while($registro = $respuesta->fetch_object())
        {
            $data = array(
                "0"=>(($registro->estado=='Aceptado')?'<button class="btn btn-warning" onclick="MostrarRegistroVenta('.$registro->idventa.')"><i class="fa fa-pencil"></i></button>'.'<button class="btn btn-danger" onclick="AnularVenta('.$registro->idventa.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning" onclick="MostrarRegistroVentaAnulado('.$registro->idventa.')"><i class="fa fa-eye"></i></button>'),
                "1"=>$registro->fecha,
                "2"=>$registro->cliente,
                "3"=>$registro->usuario,
                "4"=>$registro->tipo_comprobante,
                "5"=>$registro->serie_comprobante.'-'.$registro->num,
                "6"=>$registro->total_venta,
                "7"=>($registro->estado=="Aceptado")?'<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Anulado<span>'
            );
        }
        $resultado= array(
            "sEcho"=>1,
            "iTotalRecords"=>count($data),
            "iTotalDisplayRecords"=>count($data),
            "aaData"=>$data);
            echo json_encode($resultado);
    break;
}

?>