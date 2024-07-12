<?php
require "../config/conexion.php";

class CArticulo
{
    //Implementando el Constructor para poder crear instancias de esta clase
    public function __construct()
    {
        
    }

    //Metodo para insertar un articulo en la base de datos
    public function InsertarArticulo($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen)
    {
        $sql = "INSERT INTO articulo (idcategoria, codigo, nombre, stock, descripcion, imagen, condicion) VALUES ('$idcategoria', '$codigo', '$nombre', '$stock', '$descripcion', '$imagen', '1')";
        return Ejecutar_Consulta($sql);
    }

    //Metodo para editar un articulo
    public function ActualizarArticulo($idarticulo,$idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen)
    {
        $sql = "UPDATE articulo SET idcategoria='$idcategoria', codigo='$codigo', nombre='$nombre', stock='$stock', descripcion='$descripcion', imagen='$imagen' WHERE idarticulo='$idarticulo'";
        return Ejecutar_Consulta($sql);
    }

    //Metodo para desactivar un articulo en el caso que no haya existencias de este
    public function DesactivarArticulo($idarticulo)
    {
        $sql = "UPDATE articulo SET condicion='0' WHERE idarticulo='$idarticulo'";
        return Ejecutar_Consulta($sql);
    }

    //Metodo para activar un articulo en los casos que se haga un restock del mismo.
    public function ActivarArticulo($idarticulo)
    {
        $sql = "UPDATE articulo SET condicion='1' WHERE idarticulo='$idarticulo'";
        return Ejecutar_Consulta($sql);
    }

    //Metodo para seleccionar un registro y poder editarlo
    public function SeleccionarArticuloEditar($idarticulo)
    {
        $sql = "SELECT *FROM articulo WHERE idarticulo='$idarticulo'";
        return EjecutarConsultaSimpleFila($sql);
    }

    //Metodo para listar todos los articulos almacenadas
    public function MostrarArticulo()
    {
        $sql = "SELECT a.idarticulo, a.idcategoria, c.nombre as categoria, a.codigo, a.nombre, a.stock, a.descripcion, a.imagen, a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria";
        return Ejecutar_Consulta($sql);
    }

    public function MostrarArticuloActivo()
    {
        $sql = "SELECT a.idarticulo, a.idcategoria, c.nombre as categoria, a.codigo, a.nombre, a.stock, a.descripcion, a.imagen, a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
        return Ejecutar_Consulta($sql);
    }
}
?>