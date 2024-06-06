<?php
require_once "../modelos/CCategoria.php";

$categoria = new CCategoria();

$idcategoria = isset($_POST["idcategoria"])?LimpiarCadena($_POST["idcategoria"]):"";
$nombre = isset($_POST["nombre"])?LimpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])?LimpiarCadena($_POST["descripcion"]):"";

switch ($_GET["operacion"])
{
    case 'EditaryGuardar':
        if (empty($idcategoria))
        {
            $respuesta = $categoria -> InsertarCategoria($nombre,$descripcion);
            echo $respuesta ? "Categoria Guardada Correctamente" : "Error, No Se Pudo Registrar la Categoria";
        }
        else
        {
            $respuesta = $categoria -> ActualizarCategoria($idcategoria, $nombre, $descripcion);
            echo $respuesta ? "Se Actualizo Correctamente la Categoria" : "Error al Actualizar la Categoria";
        }    
    break;
    case 'Seleccionar':
        $respuesta = $categoria -> SeleccionarCategorias($idcategoria);
        echo json_encode($respuesta);
        break;
    case 'Listar':
            $respuesta = $categoria->ListarCategorias();
            $data = Array();

            while ($registro = $respuesta -> fetch_object())
            {
                $data[] = array(
                    "0"=>($registro->condicion)?'<button class="btn btn-warning" onclick="SeleccionarRegistro('.$registro->idcategoria.')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-danger" onclick="DesactivarCategorias('.$registro->idcategoria.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning" onclick="SeleccionarRegistro('.$registro->idcategoria.')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-primary" onclick="ActivarCategorias('.$registro->idcategoria.')"><i class="fa fa-check"></i></button>',
                    "1"=>$registro -> nombre,
                    "2"=>$registro -> descripcion,
                    "3"=>$registro -> condicion?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
                );
            }

            $resultado = array(
                "sEcho" => 1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data
            );
            echo json_encode($resultado);
        break;
    case 'Desactivar':
        $respuesta = $categoria -> DesactivarCategorias($idcategoria);
        echo $respuesta ? "Categoria Desactivada Correctamente" : "Error, No Se Puede Desactivar la Categoria";
        break;
    case 'Activar':
        $respuesta = $categoria -> ActivarCategorias($idcategoria);
        echo $respuesta ? "Categoria Activada Correctamente" : "Error, No Se Pudo Activar la Categoria";
        break;
}
?>