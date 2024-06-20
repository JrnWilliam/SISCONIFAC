<?php
require "../config/conexion.php";

class CPersona
{
    //Implementando el constructor para poder crear instancias de esta clase
    public function __construct()
    {
        
    }
    //Funcion encargada de Insertar un registro a la tabla persona
    public function InsertarRegistroPersona($tipopersona,$nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email)
    {
        $sql = "INSERT INTO persona(tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email) VALUES ('$tipopersona','$nombre','$tipodocumento','$numdocumento','$direccion','$telefono','$email')";
        return Ejecutar_Consulta($sql);
    }
    //Funcion Encargada de Editar un registro de la tabla persona
    public function ActualizarRegistroPersona($idpersona,$tipopersona,$nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email)
    {
        $sql = "UPDATE persona set tipo_persona='$tipopersona',nombre='$nombre',tipo_documento='$tipodocumento',num_documento='$numdocumento',direccion='$direccion',telefono='$telefono',email='$email' WHERE idpersona='$idpersona'";
        return Ejecutar_Consulta($sql);
    }

    //Funcion encargada de eliminar el registro de una persona
    public function EliminarRegistroPersona($idpersona)
    {
        $sql = "DELETE FROM persona WHERE idpersona='$idpersona'";
        return Ejecutar_Consulta($sql);
    }

    //Funcion que nos ayudara a seleccionar un solo registro de la tabla persona para poder editarlo
    public function SeleccionarRegistroPersona($idpersona)
    {
        $sql = "SELECT *FROM persona WHERE idpersona='$idpersona'";
        return EjecutarConsultaSimpleFila($sql);
    }

    //Funcion encargada de Mostrar solo los registros de la tabla persona referente a los proveedores
    public function MostrarRegistrosProveedores()
    {
        $sql = "SELECT *FROM persona WHERE tipo_persona='Proveedor'";
        return Ejecutar_Consulta($sql);
    }

    //Funcion encargada de Mostrar solo los registros de la tabla persona referente a los clientes
    public function MostrarRegistrosClientes()
    {
        $sql = "SELECT *FROM persona WHERE tipo_persona='Cliente'";
        return Ejecutar_Consulta($sql);
    }
}
?>