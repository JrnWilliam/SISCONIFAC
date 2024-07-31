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
    case 'SeleccionarRegistroVentas':
        $respuesta = $ventas->SeleccionarRegistroVentas($idventa);
        echo json_encode($respuesta);
    break;
    case 'MostrarVentas':
        $respuesta = $ventas->MostrarVentas();
        $data = Array();

        while($registro = $respuesta->fetch_object())
        {
            if($registro->tipo_comprobante=='Voucher')
            {
                $url = '../reportes/TVenta.php?id=';
            }
            else
            {
                $url = '../reportes/FVenta.php?id=';
            }
            $data[] = array(
                "0"=>(($registro->estado=='Aceptado')?'<button class="btn btn-warning" onclick="SeleccionarRegistroVenta('.$registro->idventa.')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-danger" onclick="AnularVenta('.$registro->idventa.')"><i class="fa fa-close"></i></button>'.' <a target="_blank" href="'.$url.$registro->idventa.'"><button class="btn btn-info" type="button"><i class="fa fa-file"></i></button></a>':'<button class="btn btn-warning" onclick="SeleccionarRegistroVentaAnulada('.$registro->idventa.')"><i class="fa fa-eye"></i></button>'),
                "1"=>$registro->fecha,
                "2"=>$registro->cliente,
                "3"=>$registro->usuario,
                "4"=>$registro->tipo_comprobante,
                "5"=>$registro->serie_comprobante.'-'.$registro->num_comprobante,
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
    case "SeleccionarDetalleVentas":
        $id = $_GET['id'];
        $respuesta = $ventas->SeleccionarRegistroDetalleVentas($id);
        $total = 0;
        echo '<thead style="background-color:#B4F8C8">
                        <th>Opciones</th>
                        <th>Artículo</th>
                        <th>Cantidad</th>
                        <th>Precio Venta</th>
                        <th>Descuento</th>
                        <th>Sub Total</th>
                </thead>';
        while($registro = $respuesta->fetch_object())
        {
            echo '<tr class="filas" id="fila'.$registro->idarticulo.'">'.
            '<td><button type="button" class="btn btn-danger" onclick="EliminarDetalleVenta('.$registro->idarticulo.')"><i class="fa fa-close"></i></button></td>'.
            '<td><input type="hidden" name="idarticulo[]" value="'.$registro->idarticulo.'">'.$registro->nombre.'</td>'.
            '<td><input type="number" name="cantidad[]" id="cantidad'.$registro->idarticulo.'" value="'.$registro->cantidad.'" oninput="ModificarSubtotales(); EvaluarVenta();"></td>'.
            '<td><input type="number" name="precioventa[]" id="precioventa'.$registro->idarticulo.'" value="'.$registro->precio_venta.'" oninput="ModificarSubtotales(); EvaluarVenta();"></td>'.
            '<td><input type="number" name="descuento[]" value="'.$registro->descuento.'" oninput="ModificarSubtotales(); EvaluarVenta();"></td>'.
            '<td><span name="subtotal" id="subtotal'.$registro->idarticulo.'">'.(($registro->precio_venta*$registro->cantidad)-$registro->descuento).'</span></td>'.
            '</tr>';
            $total = $total +(($registro->precio_venta*$registro->cantidad)-$registro->descuento);
        }
        echo '<tfoot>
                <th>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><h4 id="total">C$ '.$total.'</h4><input type="hidden" name="totalventa" id="totalventa"></th>
            </tfoot>';
    break;
    case "SeleccionarDetalleVentaAnulada":
        $id = $_GET['id'];
        $respuesta = $ventas->SeleccionarRegistroDetalleVentas($id);
        $total = 0;

        echo '<thead style="background-color:#B4F8C8">
                        <th>Opciones</th>
                        <th>Artículo</th>
                        <th>Cantidad</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Sub Total</th>
                </thead>';
        while($registro = $respuesta->fetch_object())
        {
            echo '<tr class="filas">'.
            '<td></td>'.
            '<td>'.$registro->nombre.'</td>'.
            '<td>'.$registro->cantidad.'</td>'.
            '<td>'.$registro->precio_venta.'</td>'.
            '<td>'.$registro->descuento.'</td>'.
            '<td>'.number_format($registro->subtotal,2,'.','').'</td>'.
            '</tr>';
            $total = $total + (($registro->precio_venta*$registro->cantidad)-(($registro->precio_venta*$registro->cantidad)*($registro->descuento/100)));
        }
        echo '<tfoot>
                <th>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><h4 id="total">C$ '.number_format($total,2,'.',',').'</h4><input type="hidden" name="totalventa" id="totalventa"></th>
            </tfoot>';
    break;
    case "SeleccionarCliente":
        require_once "../modelos/CPersona.php";
        $ObjPersona = new CPersona();

        $respuesta = $ObjPersona->MostrarRegistrosClientes();

        while($registro = $respuesta->fetch_object())
        {
            echo '<option value='.$registro->idpersona.'>'.$registro->nombre.'</option>';
        }
    break;
    case "MostrarArticulos":
        require_once "../modelos/CArticulo.php";
        $ObjArticulo = new CArticulo();

        $respuesta = $ObjArticulo->MostrarArticulosActivosVenta();
        $data = Array();

        while($registro = $respuesta->fetch_object())
        {
            $data[] = array(
                "0"=>'<button class="btn btn-warning" onclick="AgregarDetalleVenta('.$registro->idarticulo.',\''.$registro->nombre.'\',\''.$registro->precioventa.'\')"><span class="fa fa-plus"></span></button>',
                "1"=>$registro->nombre,
                "2"=>$registro->categoria,
                "3"=>$registro->codigo,
                "4"=>'<input type="number" id="cantidad'.$registro->idarticulo.'" min="1" value="1" class="form-control">',
                "5"=>$registro->stock,
                "6"=>$registro->precioventa,
                "7"=>"<img src='../files/articulos/".$registro->imagen."' height='50px' width='50px'>"
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