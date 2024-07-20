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

    public function EditarVentas($idventa,$idcliente,$idusuario,$tipocomprobante,$seriecomprobante,$numcomprobante,$fechahora,$impuesto,$totalventa,$idarticulo,$cantidad,$precioventa,$descuento)
    {
        $sql = "UPDATE ventas SET idcliente='$idcliente',idusuario='$idusuario',tipo_comprobante='$tipocomprobante',serie_comprobante='$seriecomprobante',num_comprobante='$numcomprobante',fecha_hora='$fechahora',impuesto='$impuesto',total_venta='$totalventa' WHERE idventa='$idventa'";
        Ejecutar_Consulta($sql);

        $deleteventas = "DELETE FROM detalle_venta WHERE idventa='$idventa'";
        Ejecutar_Consulta($deleteventas);

        $numventas = 0;
        $centinela = false;

        while($numventas < count($idarticulo))
        {
            $consulta = "INSERT INTO detalle_venta(idventa,idarticulo,cantidad,precio_venta,descuento) VALUES ('$idventa','$idarticulo[$numventas]','$cantidad[$numventas]','$precioventa[$numventas]','$descuento[$numventas]')";
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

    public function SeleccionarRegistroVentas($idventa)
    {
        $sql = "SELECT v.idventa,DATE(v.fecha_hora) AS fecha,v.idcliente,p.nombre AS cliente,u.idusuario,u.nombre AS usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM ventas v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
        return EjecutarConsultaSimpleFila($sql);
    }

    public function SeleccionarRegistroDetalleVentas($idventa)
    {
        $sql = "SELECT dv.idventa,dv.idarticulo,a.nombre,dv.cantidad,dv.precio_venta,dv.descuento,(dv.cantidad * dv.precio_venta - dv.descuento) AS subtotal FROM detalle_venta dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idventa='$idventa'";
        return Ejecutar_Consulta($sql);
    }

    public function MostrarVentas()
    {
        $sql = "SELECT v.idventa,DATE(v.fecha_hora) AS fecha,v.idcliente,p.nombre AS cliente,u.idusuario,u.nombre AS usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM ventas v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idventa DESC";
        return Ejecutar_Consulta($sql);
    }
    
}
?>