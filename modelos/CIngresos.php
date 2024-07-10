<?php
require "../config/conexion.php";

Class CIngresos
{
    public function __construct()
    {

    }

    public function RegistrarIngreso($idproveedor,$idusuario,$tipocomprobante,$seriecomprobante,$numcomprobante,$fechahora,$impuesto,$totalcompra,$idarticulo,$cantidad,$preciocompra,$precioventa)
    {
        $sql = "INSERT INTO ingreso (idproveedor,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_compra,estado) VALUES ('$idproveedor','$idusuario','$tipocomprobante','$seriecomprobante','$numcomprobante','$fechahora','$impuesto','$totalcompra','Aceptado')";

        $idnuevoingreso = EjecutarConsultaRetornarID($sql);

        $numingresos = 0;
        $centinela = true;

        while($numingresos < count($idarticulo))
        {
            $consulta = "INSERT INTO detalle_ingreso (idingreso,idarticulo,cantidad,precio_compra,precio_venta) VALUES ('$idnuevoingreso','$idarticulo[$numingresos]','$cantidad[$numingresos]','$preciocompra[$numingresos]','$precioventa[$numingresos]')";
            Ejecutar_Consulta($consulta) || $centinela = false;
            $numingresos+=1;
        }
    }

    public function AnularIngreso($idingreso)
    {
        $sql = "UPDATE ingreso SET estado='Anulado' WHERE idingreso='$idingreso'";
        return Ejecutar_Consulta($sql);
    }
}
?>