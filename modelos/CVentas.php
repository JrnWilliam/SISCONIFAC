<?php
require "../config/conexion.php";

Class CVentas
{
    public function __construct()
    {
        
    }

    public function InsertarVentas($idcliente,$idusuario,$tipocomprobante,$seriecomprobante,$numcomprobante,$fechahora,$impuesto,$totalventa,$idarticulo,$cantidad,$precioventa,$descuento)
    {
        $sql = "INSERT INTO venta(idcliente, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, total_venta, estado) VALUES ('$idcliente','$idusuario','$tipocomprobante','$seriecomprobante','$numcomprobante','$fechahora','$impuesto','$totalventa','Aceptado')";
        $idventanueva = EjecutarConsultaRetornarID($sql);

        $numventas = 0;
        $centinela = true;

        while($numventas < count($idarticulo))
        {
            $consulta = "INSERT INTO detalle_venta(idventa,idarticulo,cantidad,precio_venta,descuento) VALUES ('$idventanueva','$idarticulo[$numventas]','$cantidad[$numventas]','$precioventa[$numventas]','$descuento[$numventas]')";
            Ejecutar_Consulta($consulta) or $centinela = false;
            $numventas+=1;
        }
        return $centinela;
    }

    public function AnularVentas($idventa)
    {
        $sql = "UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
        return Ejecutar_Consulta($sql);
    }
}
?>