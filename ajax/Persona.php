<?php
require_once "../modelos/CPersona.php";

$persona = new CPersona();

$idpersona = isset($_POST["idpersona"])?LimpiarCadena($_POST["idpersona"]):"";
$tipopersona = isset($_POST["tipopersona"])?LimpiarCadena($_POST["tipopersona"]):"";
$nombre = isset($_POST["nombre"])?LimpiarCadena($_POST["nombre"]):"";
$tipodocumento = isset($_POST["tipodocumento"])?LimpiarCadena($_POST["tipodocumento"]):"";
$numdocumento = isset($_POST["numdocumento"])?LimpiarCadena($_POST["numdocumento"]):"";
$direccion = isset($_POST["direccion"])?LimpiarCadena($_POST["direccion"]):"";
$telefono = isset($_POST["telefono"])?LimpiarCadena($_POST["telefono"]):"";
$email = isset($_POST["email"])?LimpiarCadena($_POST["email"]):"";

switch ($_GET["operacion"])
{
    case 'EditaryGuardar':
        if(empty($idpersona))
        {
            $respuesta = $persona->InsertarRegistroPersona($tipopersona,$nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email);
            echo $respuesta ? "Se Guardo correctamente el Registro de la Persona" : "Error, No se pudo Guardar el Registro de la Persona";
        }
        else
        {
            $respuesta = $persona->ActualizarRegistroPersona($idpersona,$tipopersona,$nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email);
            echo $respuesta ? "Se Actualizo Correctamente el Registro de la Persona" : "Error, No se Logro Actualizar el Registro de la Persona";
        }
        break;
    case 'Eliminar':
        $respuesta = $persona->EliminarRegistroPersona($idpersona);
        echo $respuesta ? "Se Elimino Correctamente el Registro de la Persona" : "Error, No se Logro Eliminar el Registro de  la Persona";
    break;
    case 'Seleccionar':
        $respuesta = $persona->SeleccionarRegistroPersona($idpersona);
        echo json_encode($respuesta);
    break;
    case 'MostrarProveedores':
        $respuesta = $persona->MostrarRegistrosProveedores();
        $data = Array();

        while($registro = $respuesta->fetch_object())
        {
            $data[] = array(
                "0"=>'<button class="btn btn-warning" onclick="SeleccionarRegistroProveedor('.$registro->idpersona.')" title="Editar Registro Proveedor"><li class="fa fa-pencil"></li></button>'.' <button class="btn btn-danger" title="Eliminar Registro Proveedor" onclick="EliminarRegistroProveedor('.$registro->idpersona.')"><li class="fa fa-trash"></li></button>',
                "1"=>$registro->nombre,
                "2"=>$registro->tipo_documento,
                "3"=>$registro->num_documento,
                "4"=>$registro->telefono,
                "5"=>$registro->email
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
    case 'MostrarClientes':
        $respuesta = $persona->MostrarRegistrosClientes();
        $data = Array();

        while($registro=$respuesta->fetch_object())
        {
            $data[] = array(
                "0"=>'<button class="btn btn-warning" onclick="SeleccionarRegistroCliente('.$registro->idpersona.')" title="Editar Registro Cliente"><li class="fa fa-pencil"></li></button>'.' <button class="btn btn-danger" title="Eliminar Registro Cliente" onclick="EliminarRegistroCliente('.$registro->idpersona.')"><li class="fa fa-trash"></li></button>',
                "1"=>$registro->nombre,
                "2"=>$registro->tipo_documento,
                "3"=>$registro->num_documento,
                "4"=>$registro->telefono,
                "5"=>$registro->email
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
}
?>