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
}
?>