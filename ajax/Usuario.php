<?php
session_start();
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
            $extension = explode(".", $_FILES["imagen"]["name"]);

            if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
            {
                $imagen = round(microtime(true)). '.' . end($extension);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
            }
        }
        
        $pass = hash("SHA256",$clave);

        if(empty($idusuario))
        {
            $respuesta = $usuario->InsertarUsuario($nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email,$cargo,$login,$pass,$imagen,isset($_POST['permiso']) ? $_POST['permiso'] : []);
            echo $respuesta ? "Se Guardo Correctamente el Nuevo Usuario" : "Error, No se Pudo Crear el Nuevo Usuario";
        }
        else
        {
            $respuesta = $usuario->EditarUsuario($idusuario,$nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email,$cargo,$login,$pass,$imagen,isset($_POST['permiso']) ? $_POST['permiso'] : []);
            echo $respuesta ? "Se Edito Correctamente el usuario" : "Error, No se Logro Guardar El Usuario Actualizado";
        }
    break;
    case 'SeleccionarUsuario':
        $respuesta = $usuario->SeleccionarUsuarioEditar($idusuario);
        echo json_encode($respuesta);
    break;
    case 'MostrarUsuarios':
        $respuesta = $usuario->MostrarUsuarios();
        
        $data =  Array();

        while($registro = $respuesta -> fetch_object())
        {
            $data[] = array(
                "0"=>($registro->condicion)?'<button class="btn btn-warning" onclick="SeleccionarRegistroUsuario('.$registro->idusuario.')" title="Editar Usuario"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-danger" onclick="DesactivarUsuario('.$registro->idusuario.')" title="Desactivar Usuario"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning" onclick="SeleccionarRegistroUsuario('.$registro->idusuario.')" title="Editar Usuario"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-primary" onclick="ActivarUsuario('.$registro->idusuario.')" title="Activar Usuario"><i class="fa fa-check"></i></button>',
                "1"=>$registro->nombre,
                "2"=>$registro->num_documento,
                "3"=>$registro->telefono,
                "4"=>$registro->email,
                "5"=>$registro->cargo,
                "6"=>$registro->login,
                "7"=>"<img src='../files/usuarios/".$registro->imagen."' height='50px' width='50px'>",
                "8"=>($registro->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
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
    case 'DesactivarUsuario':
        $resultado = $usuario->DesactivarUsuario($idusuario);
        echo $resultado ? "Usuario Desactivado Correctamente" : "Error, No Se Pudo Desactivar el Usuario";
    break;
    case 'ActivarUsuario':
        $resultado = $usuario->ActivarUsuario($idusuario);
        echo $resultado ? "Usuario Activado Correctamente" : "Error, No se Pudo Desactivar el Usuario";
    break;
    case 'Permisos':
        require_once '../modelos/CPermiso.php';
        $permiso = new CPermiso();
        $respuesta = $permiso ->MostrarPermisos();

        $iduser = $_GET['iduser'];
        $permisosmarcados = $usuario -> ListarPermisosMarcados($iduser);

        $valores = array();

        while($permarc = $permisosmarcados->fetch_object())
        {
            array_push($valores, $permarc->idpermiso);
        }

        while($registro = $respuesta->fetch_object())
        {
            $chk = in_array($registro->idpermiso,$valores)?'checked':'';
            echo '<li><input type="checkbox" '.$chk.' name="permiso[]" value="'.$registro->idpermiso.'">'.$registro->nombre.'</li>';
        }
    break;
    case 'VerificarSesion':
        $loginacceso=$_POST['loginacceso'];
        $claveacceso=$_POST['claveacceso'];

        $passwd = hash("SHA256",$claveacceso);

        $respuesta = $usuario->VerificarSesion($loginacceso,$passwd);

        $extraer = $respuesta->fetch_object();

        if(isset($extraer))
        {
            $_SESSION['idusuario']=$extraer->idusuario;
            $_SESSION['nombre']=$extraer->nombre;
            $_SESSION['imagen']=$extraer->imagen;
            $_SESSION['login']=$extraer->login;
            $_SESSION['cargo']=$extraer->cargo;

            $marcados = $usuario->ListarPermisosMarcados($extraer->idusuario);

            $valor = array();

            while($permisos = $marcados->fetch_object())
            {
                array_push($valor,$permisos->idpermiso);
            }

            in_array(1,$valor)?$_SESSION['escritorio']=1:$_SESSION['escritorio']=0;
            in_array(2,$valor)?$_SESSION['almacen']=1:$_SESSION['almacen']=0;
            in_array(3,$valor)?$_SESSION['compras']=1:$_SESSION['compras']=0;
            in_array(4,$valor)?$_SESSION['ventas']=1:$_SESSION['ventas']=0;
            in_array(5,$valor)?$_SESSION['acceso']=1:$_SESSION['acceso']=0;
            in_array(6,$valor)?$_SESSION['consultacompras']=1:$_SESSION['consultacompras']=0;
            in_array(7,$valor)?$_SESSION['consultaventas']=1:$_SESSION['consultaventas']=0;
        }

        echo json_encode($extraer);
    break;
    case 'CerrarSesion':
        session_unset();

        session_destroy();

        header('Location: ../index.php');
    break;
}
?>