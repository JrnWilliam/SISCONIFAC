<?php
require "../config/conexion.php";

class CCategoria
{
    public function __construct()
    {
        
    }

    //Metodo Para Insertar Registros en la tabla categoria
    public function InsertarCategoria($nombre, $descripcion)
    {
        $sql = "INSERT INTO categoria (nombre,descripcion,condicion) VALUES ('$nombre','$descripcion','1')";
        return Ejecutar_Consulta($sql);
    }

    //Metodo para actualizar cualquier categoria que se encuentre en mi tabla categoria
    public function ActualizarCategoria($idcategoria,$nombre,$descripcion)
    {
        $sql = "UPDATE categoria SET nombre= '$nombre', descripcion= '$descripcion' WHERE idcategoria= '$idcategoria'";
        return Ejecutar_Consulta($sql);
    }

    //Metodo que se encarga de desactivar las categorias
    public function DesactivarCategorias($idcategoria,)
    {
        $sql = "UPDATE categoria SET condicion='0' WHERE idcategoria='$idcategoria'";
        return Ejecutar_Consulta($sql);
    }

    //Metodo que se encarga de activar las categorias
    public function ActivarCategorias($idcategoria)
    {
        $sql = "UPDATE categoria SET condicion='1' WHERE idcategoria='$idcategoria'";
        return Ejecutar_Consulta($sql);
    }

    //Metodo para mostrar los datos de X registro que se quiera modificar
    public function SeleccionarCategorias($idcategoria)
    {
        $sql = "SELECT *FROM categoria WHERE idcategoria='$idcategoria'";
        return EjecutarConsultaSimpleFila($sql);
    }

    //Metodo para Listar las categorias guardadas
    public function ListarCategorias()
    {
        $sql = "SELECT *FROM categoria";
        return Ejecutar_Consulta($sql);
    }

    //Metodo para seleccionar las categorias activas y mostrarlas en el select de la vista articulos
    public function SeleccionarCategoriasArticulo()
    {
        $sql = "SELECT *FROM categoria WHERE condicion=1";
        return Ejecutar_Consulta($sql);
    }
}
?>