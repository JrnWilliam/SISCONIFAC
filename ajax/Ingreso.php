<?php
session_start();

require_once '../modelos/CIngresos.php';

$ingreso = new CIngresos();

$idingreso = isset($_POST['idingreso'])?LimpiarCadena($_POST['idingreso']):"";
$idproveedor= isset($_POST['idproveedor'])?LimpiarCadena($_POST['idproveedor']):"";
$idusuario= $_SESSION["idusuario"];
$tipocomprobante= isset($_POST['tipocomprobante'])?LimpiarCadena($_POST['tipocomprobante']):"";
$seriecomprobante= isset($_POST['seriecomprobante'])?LimpiarCadena($_POST['seriecomprobante']):"";
$numcomprobante= isset($_POST['numcomprobante'])?LimpiarCadena($_POST['numcomprobante']):"";
$fechahora= isset($_POST['fechahora'])?LimpiarCadena($_POST['fechahora']):"";
$impuesto= isset($_POST['impuesto'])?LimpiarCadena($_POST['impuesto']):"";
$totalcompra= isset($_POST['totalcompra'])?LimpiarCadena($_POST['totalcompra']):"";

switch($_GET["Operacion"])
{
    case "GuardaryEditar":
        if(empty($idingreso))
        {
            $respuesta = $ingreso->RegistrarIngreso($idproveedor,$idusuario,$tipocomprobante,$seriecomprobante,$numcomprobante,$fechahora,$impuesto,$totalcompra,$_POST['idarticulo'],$_POST['cantidad'],$_POST['preciocompra'],$_POST['precioventa']);
            echo $respuesta ? "Se Registro Un Ingreso Correctamente" : "Error, No Se Logro Registrar el Ingreso";
        }
    break;
    case 'AnularIngreso':
        $respuesta = $ingreso->AnularIngreso($idingreso);
        echo $respuesta ? "Ingreso Anulado Correctamente" : "Error, No Se Logro Anular el Ingreso";
    break;
    case 'SeleccionarRegistroIngreso':
        $respuesta = $ingreso->SeleccionarRegistroIngresos($idingreso);
        echo json_encode($respuesta);
    break;
    case 'MostrarIngresos':
        $respuesta = $ingreso -> MostrarIngresos();

        $data = Array();

        while($registro = $respuesta->fetch_object())
        {
            $data[] = array(
                "0"=>($registro->estado=='Aceptado')?'<button class="btn btn-warning" onclick="MostrarRegistroIngreso('.$registro->idingreso.')"><i class="fa fa-pencil"></i></button>'.'<button class= "btn btn-danger" onclick="AnularIngreso('.$registro->idingreso.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning" onclick="MostrarRegistroIngreso('.$registro->idingreso.')"><i class="fa fa-pencil"></i></button>',
                "1"=>$registro->fecha,
                "2"=>$registro->proveedor,
                "3"=>$registro->usuario,
                "4"=>$registro->tipo_comprobante,
                "5"=>$registro->serie_comprobante,
                "6"=>$registro->total_compra,
                "7"=>($registro->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Cerrada</span>'
            );
        }
        $resultado = array(
            "sEcho"=>1,
            "iTotalRecords"=>count($data),
            "iTotalDisplayRecords"=>count($data),
            "aaData"=>$data);
            echo json_encode($resultado);
    break;
}
?>