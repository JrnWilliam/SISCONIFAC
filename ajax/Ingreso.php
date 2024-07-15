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
            $respuesta = $ingreso->RegistrarIngreso($idproveedor,$idusuario,$tipocomprobante,$seriecomprobante,$numcomprobante,$fechahora,$impuesto,$totalcompra,$_POST["idarticulo"],$_POST["cantidad"],$_POST["preciocompra"],$_POST["precioventa"]);
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
                "0"=>($registro->estado=='Aceptado')?'<button class="btn btn-warning" onclick="MostrarRegistroIngreso('.$registro->idingreso.')"><i class="fa fa-eye"></i></button>'.' <button class= "btn btn-danger" onclick="AnularIngreso('.$registro->idingreso.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning" onclick="MostrarRegistroIngreso('.$registro->idingreso.')"><i class="fa fa-eye"></i></button>',
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
    case 'SeleccionarDetallesIngresos':
        $id = $_GET['id'];
        $respuesta = $ingreso->SeleccionarRegistroDetalleIngreso($id);
        $total = 0;

        echo '<thead style="background-color:#B4F8C8">
                        <th>Opciones</th>
                        <th>Artículo</th>
                        <th>Cantidad</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Sub Total</th>
                        <th>Opciones</th>
                </thead>';
        while($registro = $respuesta->fetch_object())
        {
            echo '<tr class="filas">'.
            '<td></td>'.
            '<td>'.$registro->nombre.'</td>'.
            '<td>'.$registro->cantidad.'</td>'.
            '<td>'.$registro->precio_compra.'</td>'.
            '<td>'.$registro->precio_venta.'</td>'.
            '<td>'.$registro->precio_compra*$registro->cantidad.'</td>'.
            '<td></td>'.
            '</tr>';
            $total = $total + ($registro->precio_compra*$registro->cantidad);
        }
        echo '<tfoot>
                <th>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Opciones</th>
                    <th><h4 id="total">C$ '.$total.'</h4><input type="hidden" name="totalcompra" id="totalcompra"></th>
            </tfoot>';
    break;
    case 'SeleccionarProveedor':
        require_once '../modelos/CPersona.php';
        $ObjPersona = new CPersona();

        $respuesta = $ObjPersona->MostrarRegistrosProveedores();

        while($registro = $respuesta->fetch_object())
        {
            echo '<option value='.$registro->idpersona.'>'.$registro->nombre.'</option>';
        }
    break;
    case 'MostrarArticulos':
        require_once '../modelos/CArticulo.php';
        $ObjArticulo = new CArticulo();

        $respuesta = $ObjArticulo->MostrarArticuloActivo();

        $data = Array();

        while($registro = $respuesta->fetch_object())
        {
            $data[] = array(
                "0"=>'<button class="btn btn-warning" onclick="AgregarDetalleCompra('.$registro->idarticulo.',\''.$registro->nombre.'\')"><span class="fa fa-plus"></span></button>',
                "1"=>$registro->nombre,
                "2"=>$registro->categoria,
                "3"=>$registro->codigo,
                "4"=>$registro->stock,
                "5"=>"<img src='../files/articulos/".$registro->imagen."' height='50px' width='50px'>"
            );
        }
        $resultado = array(
            "sEcho"=> 1,
            "iTotalRecords"=>count($data),
            "iTotalDisplayRecords"=>count($data),
            "aaData"=>$data
        );
        echo json_encode($resultado);
    break;
}
?>