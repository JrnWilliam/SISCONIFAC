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
        return $centinela;
    }

    public function EditarIngreso($idingreso,$idproveedor,$tipocomprobante,$seriecomprobante,$numcomprobante,$fechahora,$impuesto,$totalcompra,$idarticulo,$cantidad,$preciocompra,$precioventa)
    {
        $sql = "UPDATE ingreso SET idproveedor='$idproveedor', tipo_comprobante='$tipocomprobante',serie_comprobante='$seriecomprobante',num_comprobante='$numcomprobante',fecha_hora='$fechahora',impuesto='$impuesto',total_compra='$totalcompra' WHERE idingreso='$idingreso'";
        Ejecutar_Consulta($sql);

        $deleteingreso = "DELETE FROM detalle_ingreso WHERE idingreso='$idingreso'";
        Ejecutar_Consulta($deleteingreso);

        $numingresos = 0;
        $centinela = true;

        while($numingresos < count($idarticulo))
        {
            $consulta = "INSERT INTO detalle_ingreso(idingreso,idarticulo,cantidad,precio_compra,precio_venta) VALUES ('$idingreso','$idarticulo[$numingresos]','$cantidad[$numingresos]','$preciocompra[$numingresos]','$precioventa[$numingresos]')";
            Ejecutar_Consulta($consulta) or $centinela = false;
            $numingresos+=1;
        }
        return $centinela;
    }

    public function AnularIngreso($idingreso)
    {
        $sql = "UPDATE ingreso SET estado='Anulado' WHERE idingreso='$idingreso'";
        return Ejecutar_Consulta($sql);
    }

    public function SeleccionarRegistroIngresos($idingreso)
    {
        $sql = "SELECT i.idingreso, DATE(i.fecha_hora) AS fecha,i.idproveedor,p.nombre AS proveedor,u.idusuario,u.nombre AS usuario,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN  persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE i.idingreso='$idingreso'";
        return EjecutarConsultaSimpleFila($sql);
    }

    public function SeleccionarRegistroDetalleIngreso($idingreso)
    {
        $sql = "SELECT di.idingreso,di.idarticulo,a.nombre,di.cantidad,di.precio_compra,di.precio_venta FROM detalle_ingreso di INNER JOIN articulo a on di.idarticulo=a.idarticulo WHERE di.idingreso='$idingreso'";
        return Ejecutar_Consulta($sql);
    }

    public function MostrarIngresos()
    {
        $sql = "SELECT i.idingreso, DATE(i.fecha_hora) AS fecha,i.idproveedor,p.nombre AS proveedor,u.idusuario,u.nombre AS usuario,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN  persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario ORDER BY i.idingreso DESC";
        return Ejecutar_Consulta($sql);
    }

    public function CabeceraFacIngreso($idingreso)
    {
        $sql = "SELECT i.idingreso,i.idproveedor,p.nombre as proveedor,p.direccion,p.tipo_documento,p.num_documento,p.email,p.telefono,i.idusuario,u.nombre AS usuario,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,DATE(i.fecha_hora) AS fecha,i.impuesto,i.total_compra FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u on i.idusuario=u.idusuario WHERE i.idingreso='$idingreso'";
        return Ejecutar_Consulta($sql);
    }

    public function DetalleFacturaIngreso($idingreso)
    {
        $sql = "SELECT a.nombre as articulo,a.codigo,di.cantidad,di.precio_compra,di.precio_venta,(di.cantidad * di.precio_compra) as subtotal FROM detalle_ingreso di INNER JOIN articulo a ON di.idarticulo = a.idarticulo WHERE di.idingreso ='$idingreso'";
        return Ejecutar_Consulta($sql);
    }

    public function GenerarNumComprobante($tipocomprobante,$seriecomprobante)
    {
        $sql = "SELECT MAX(num_comprobante) AS ultimo FROM ingreso WHERE tipo_comprobante='$tipocomprobante' AND serie_comprobante = '$seriecomprobante'";
        return Ejecutar_Consulta($sql);
    }
}
?>