<?php
require "../config/conexion.php";

class CUsuario
{
    public function __construct()
    {
        
    }

    public function InsertarUsuario($nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen)
    {
        $sql = "INSERT INTO usuario(idusuario,nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,clave,imagen,condicion) VALUES ('$nombre','$tipodocumento','$numdocumento','$direccion','$telefono','$email','$cargo','$login','$clave','$imagen','1')";
        Ejecutar_Consulta($sql);
    }

    public function EditarUsuario($idusuario,$nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen)
    {
        $sql = "UPDATE usuario SET nombre='$nombre',tipo_documento='$tipodocumento',num_documento='$numdocumento',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',login='$login',clave='$clave',imagen='$imagen' WHERE idusario='$idusuario'";
        return Ejecutar_Consulta($sql);
    }

    Public function DesactivarUsuario($idusuario)
    {
        $sql = "UPDATE usuario SET condicion='0' WHERE idusuario='$idusuario'";
        return Ejecutar_Consulta($sql);
    }

    public function ActivarUsuario($idusuario)
    {
        $sql = "UPDATE usuario SET condicion='0' WHERE idusuario='$idusuario'";
        return Ejecutar_Consulta($sql);
    }

    public function SeleccionarUsuarioEditar($idusuario)
    {
        $sql = "SELECT *FROM usuario WHERE idusuario='$idusuario'";
        return EjecutarConsultaSimpleFila($sql);
    }

    public function MostrarUsuarios()
    {
        $sql = "SELECT *FROM usuario";
        return Ejecutar_Consulta($sql);
    }
}
?>