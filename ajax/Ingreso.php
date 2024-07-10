<?php
session_start();

require_once '../modelos/CIngresos.php';

$ingreso = new CIngresos();

$idingreso = isset($_POST['idingreso'])?LimpiarCadena($_POST['idingreso']):"";
$idproveedor= isset($_POST['idproveedor'])?LimpiarCadena($_POST['idproveedor']):"";
$idusuario= isset($_POST['idusuario'])?LimpiarCadena($_POST['idusuario']):"";
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
}
?>