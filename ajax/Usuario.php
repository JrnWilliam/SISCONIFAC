<?php
require_once "../modelos/CUsuario.php";

$usuario = new CUsuario();

$idusuario = isset($_POST["idusuario"])?LimpiarCadena($_POST["idusuario"]):"";
$nombre = isset($_POST["nombre"])?LimpiarCadena($_POST["nombre"]):"";
$tipodocumento = isset($_POST["tipodocumento"])?LimpiarCadena($_POST["tipodocumento"]):"";
$numdocumento = isset($_POST["numdocumento"])?LimpiarCadena($_POST["numdocumento"]):"";
$direccion = isset($_POST["direccion"])?LimpiarCadena($_POST["direccion"]):"";
$telefono = isset($_POST["telefono"])?LimpiarCadena($_POST["telefono"]):"";
$email = isset($_POST["email"])?LimpiarCadena($_POST["email"]):"";
$cargo = isset($_POST["cargo"])?LimpiarCadena($_POST["cargo"]):"";
$login = isset($_POST["login"])?LimpiarCadena($_POST["login"]):"";
$clave = isset($_POST["clave"])?LimpiarCadena($_POST["clave"]):"";
$imagen = isset($_POST["imagen"])?LimpiarCadena($_POST["imagen"]):"";

switch($_GET["operacion"])
{
    case 'EditaryGuardar':
        if(!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
        {
            $imagen = isset($_POST["imagenactual"]) ? $_POST["imagenactual"] : "";
        }
        else
        {
            $extension = explode(".", $_FILES['imagen']['name']);

            if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
            {
                $imagen = round(microtime(true)). '.' . end($extension);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/".$imagen);
            }
        }
        if(empty($idusuario))
        {
            $respuesta = $usuario->InsertarUsuario($nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen);
            echo $respuesta ? "Se Guardo Correctamente el Nuevo Usuario" : "Error, No se Pudo Crear el Nuevo Usuario";
        }
        else
        {
            $respuesta = $usuario->EditarUsuario($idusuario,$nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen);
            echo $respuesta ? "Se Edito Correctamente el usuario" : "Error, No se Logro Guardar El Usuario Actualizado";
        }
    break;
    case 'Seleccionar':
        $respuesta = $usuario->SeleccionarUsuarioEditar($idusuario);
        echo json_encode($respuesta);
    break;
    case 'Mostrar':
        $respuesta = $usuario->MostrarUsuarios();
        
        $data =  Array();

        while($registro = $respuesta -> fetch_object())
        {
            $data[] = array(
                "0"=>($registro->condicion)?'<button class="btn btn-warning" onclick="SeleccionarRegistroUsuario('.$registro->idusuario.')" title="Editar Usuario"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-danger" onclick="DesactivarUsuario('.$registro->idusuario.')" title="Editar Usuario"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning" onclick="SeleccionarRegistroUsuario('.$registro->idusuario.')" title="Editar Usuario"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-primary" onclick="ActivarUsuario('.$registro->idusuario.')" title="Activar Usuario"><i class="fa fa-check"></i></button>',
                "1"=>$registro->nombre,
                "2"=>$registro->num_documento,
                "3"=>$registro->telefono,
                "4"=>$registro->email,
                "5"=>$registro->cargo,
                "7"=>$registro->login,
                "6"=>"<img src='../files/usuarios/".$registro->imagen."' height='50px' width='50px'>",
                "8"=>($registro->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label br-red">'
            );
        }
        $resultado = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" =>count($data),
            "aaData"=>$data
        );
        echo json_encode($resultado);
    break;
    case 'Desactivar':
        $resultado = $usuario->DesactivarUsuario($idusuario);
        echo $resultado ? "Usuario Desactivado Correctamente" : "Error, No Se Pudo Desactivar el Usuario";
    break;
    case 'Activar':
        $resultado = $usuario->ActivarUsuario($usuario);
        echo $resultado ? "Usuario Activado Correctamente" : "Error, No se Pudo Desactivar el Usuario";
    break;
}
?>