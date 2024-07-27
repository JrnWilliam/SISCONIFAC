<?php
require '../config/conexion.php';

Class CConsultas
{
    public function __construct()
    {

    }

    public function Compras_x_Fecha($fechainicio,$fechafin)
    {
        $sql = "SELECT DATE(i.fecha_hora) AS fecha,u.nombre AS usuario,p.nombre AS proveedor,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor = p.idpersona INNER JOIN usuario u ON i.idusuario = u.idusuario WHERE DATE(i.fecha_hora) >= '$fechainicio' AND DATE(i.fecha_hora) <= '$fechafin'";
        return Ejecutar_Consulta($sql);
    }

    public function Ventas_x_Fecha_Cliente($fechainicio,$fechafin,$idcliente)
    {
        $sql = "SELECT DATE(v.fecha_hora) AS fecha,u.nombre AS usuario,p.nombre as cliente,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM ventas v INNER JOIN persona p ON v.idcliente = p.idpersona INNER JOIN usuario u ON v.idusuario = u.idusuario WHERE DATE(v.fecha_hora) >= '$fechainicio' AND DATE(v.fecha_hora) <= '$fechafin' AND v.idcliente='$idcliente'";
        return Ejecutar_Consulta($sql);
    }
}
?>