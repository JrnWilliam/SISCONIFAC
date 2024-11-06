<?php
require_once "../modelos/CArticulo.php";

$articulo = new CArticulo();

$idarticulo = isset($_POST["idarticulo"])?LimpiarCadena($_POST["idarticulo"]):"";
$idcategoria = isset($_POST["idcategoria"])?LimpiarCadena($_POST["idcategoria"]):"";
$codigo=isset($_POST["codigo"])?LimpiarCadena($_POST["codigo"]):"";
$nombre=isset($_POST["nombre"])?LimpiarCadena($_POST["nombre"]):"";
$stock=isset($_POST["stock"])?LimpiarCadena($_POST["stock"]):"";
$descripcion=isset($_POST["descripcion"])?LimpiarCadena($_POST["descripcion"]):"";
$imagen=isset($_POST["imagen"])?LimpiarCadena($_POST["imagen"]):"";

switch ($_GET["operacion"])
{
    case 'EditaryGuardar':
        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
        {
            $imagen = isset($_POST["imagen_actual"]) ? $_POST["imagen_actual"] : "";
        }
        else
        {
            $extension = explode(".", $_FILES["imagen"]["name"]);
            if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
            {
                $imagen = round(microtime(true)) . '.' . end($extension);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" . $imagen);
                
            }
        }
        if(empty($idarticulo))
        {
            $respuesta = $articulo->InsertarArticulo($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen);
            echo $respuesta ? "Articulo Guardado Correctamente":"Error, No Se Pudo Guardar El Articulo";
        }
        else
        {
            $respuesta = $articulo -> ActualizarArticulo($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen);
            echo $respuesta ? "Se Actualizo Correctamente el Articulo":"Error, No Se Pudo Actualizar el Articulo";
        }
    break;
    case 'Seleccionar':
        $respuesta = $articulo -> SeleccionarArticuloEditar($idarticulo);
        echo json_encode($respuesta);
    break;
    case 'Listar':
        $respuesta = $articulo -> MostrarArticulo();
        $data = Array();

        while ($registro = $respuesta -> fetch_object())
        {
            $data[] = array(
                "0"=>($registro->condicion)?'<button class="btn btn-warning" onclick="SeleccionarRegistroArticulo('.$registro->idarticulo.')" title="Editar Articulo"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-danger" onclick="DesactivarArticulo('.$registro->idarticulo.')" title="Desactivar Articulo"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning" onclick="SeleccionarRegistroArticulo('.$registro->idarticulo.')" title="Editar Articulo"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-primary" onclick="ActivarArticulo('.$registro->idarticulo.')" title="Activar Articulo"><i class="fa fa-check"></i></button>',
                "1"=>$registro->nombre,
                "2"=>$registro->categoria,
                "3"=>$registro->codigo,
                "4"=>$registro->stock,
                "5"=>$registro->descripcion,
                "6"=>"<img src='../files/articulos/".$registro->imagen."' height='50px' width='50px'>",
                "7"=>($registro->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
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
    case 'Desactivar':
        $respuesta = $articulo -> DesactivarArticulo($idarticulo);
        echo $respuesta ? "Articulo Desactivado Correctamente":"Error, No Se Logro Desactivar el Articulo";
    break;
    case 'Activar':
        $respuesta = $articulo -> ActivarArticulo($idarticulo);
        echo $respuesta ? "Articulo Activado Correctamente" : "Error, No Se Logro Desactivar el Articulo";
    break;
    case 'SeleccionarCatArticulo':
        require_once "../modelos/CCategoria.php";
        $ObjCategoria = new CCategoria();

        $respuesta = $ObjCategoria->SeleccionarCategoriasArticulo();
        while($registro = $respuesta->fetch_object())
        {
            echo '<option value='.$registro->idcategoria.'>'.$registro->nombre.'</option>';
        }
    break;
}
?>