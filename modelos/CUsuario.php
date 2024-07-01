<?php
require "../config/conexion.php";

class CUsuario
{
    public function __construct()
    {
        
    }

    public function InsertarUsuario($nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
    {
        $sql = "INSERT INTO usuario(nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,clave,imagen,condicion) VALUES ('$nombre','$tipodocumento','$numdocumento','$direccion','$telefono','$email','$cargo','$login','$clave','$imagen','1')";
        $idusuarionuevo = EjecutarConsultaRetornarID($sql);
        $numpermisos = 0;
        $centinela = true;

        while($numpermisos<count($permisos))
        {
            $consulta = "INSERT INTO usuario_permiso(idusuario,idpermiso) VALUES ('$idusuarionuevo','$permisos[$numpermisos]')";
            Ejecutar_Consulta($consulta) or $centinela = false; 
            $numpermisos = $numpermisos + 1;
        }

        return $centinela;
    }

    public function EditarUsuario($idusuario,$nombre,$tipodocumento,$numdocumento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
    {
        $sql = "UPDATE usuario SET nombre='$nombre',tipo_documento='$tipodocumento',num_documento='$numdocumento',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',login='$login',clave='$clave',imagen='$imagen' WHERE idusuario='$idusuario'";
        Ejecutar_Consulta($sql);

        $permisoeliminado = "DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
        Ejecutar_Consulta($permisoeliminado);

        $numpermisos = 0;
        $centinela = true;

        while($numpermisos<count($permisos))
        {
            $consulta = "INSERT INTO usuario_permiso(idusuario,idpermiso) VALUES ('$idusuario','$permisos[$numpermisos]')";
            Ejecutar_Consulta($consulta) or $centinela = false; 
            $numpermisos = $numpermisos + 1;
        }

        return $centinela;
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

    public function ListarPermisosMarcados($idusuario)
    {
        $sql = "SELECT *FROM usuario_permiso WHERE idusuario='$idusuario'";
        return Ejecutar_Consulta($sql);
    }
}
?>