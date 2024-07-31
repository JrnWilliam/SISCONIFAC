<?php
  ob_start();
  session_start();
  
  if(!isset($_SESSION["nombre"]))
  {
    echo "Ingrese al Sistema Para Poder Generar Este Voucher";
  }
  else
  {
    if($_SESSION['ventas']==1)
    {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/voucher.css">
    <title>Voucher de Venta</title>
</head>
<body onload="window.print();">
    <?php
      require_once '../modelos/CVentas.php';
      $Objventa = new CVentas();

      $respuesta = $Objventa->CabeceraFacVenta($_GET["id"]);
      $registro = $respuesta->fetch_object();

        $empresa = "Ferreteria Mi Bendición S.A";
        $ruc = "J0310000002118";
        $direccion = "León, Nicaragua";
        $telefono = "+505 2222-2222";
        $email = "fmibendicion@ejemplo.com";
    ?>
    <div class="ZonaImprimir">
        <br>
        <table border="0" align="center" width="300px" >
        <tr>
            <td align="center">
            ◢ <strong>
                    <?php
                      echo $empresa;  
                    ?>
                </strong>◣
                <br>
                <?php
                  echo $ruc;  
                ?>
                <br>
                <?php
                  echo $direccion.' - '.$telefono;  
                ?>
                <br>
            </td>
        </tr>
        <tr>
           <td align="center">
            <?php
              echo $registro->fecha;  
            ?>
           </td> 
        </tr>
        <tr>
            <td align="center">
            </td>
        </tr>
        <tr>
            <td>
                Cliente: <?php echo $registro->cliente; ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                  echo $registro->tipo_documento.": ".$registro->num_documento;  
                ?>
            </td>
        </tr>
        <tr>
            <td>
                N° de Venta <?php echo $registro->serie_comprobante." - ".$registro->num_comprobante?>
            </td>
        </tr>
        </table>
        <br>
        <table border="0" align="center" width="300px">
            <tr>
                <td>Cantidad</td>
                <td>Descripción</td>
                <td align="right">Importe</td>
            </tr>
            <tr>
                <td colspan="3">
                ==========================================
                </td>
            </tr>
            <?php
              $respuestad = $Objventa->DetalleFacturaVenta($_GET["id"]);
              $cantidad = 0;
              
              while($registrod = $respuestad->fetch_object())
              {
                echo "<tr>";
                echo "<td>".$registrod->cantidad."</td>";
                echo "<td>".$registrod->articulo;
                echo "<td align='right'>C$ ".$registrod->subtotal."</td>";
                echo "</tr>";
                $cantidad += $registrod->cantidad;
              }
            ?>
            <tr>
                <td>
                &nbsp;
                </td>
                <td align="right">
                    <b>Total: </b>
                </td>
                <td align="right">
                    <b>
                        C$ <?php echo $registro->total_venta; ?>
                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    N° de Articulos: <?php echo $cantidad; ?>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                 &nbsp;
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    ¡Gracias Por Su Compra!
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    Ferreteria Mi Bendición.
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    León, Nicaragua
                </td>
            </tr>
        </table>
        <br>
    </div>
    <p>&nbsp;</p>
</body>
</html>
<?php
    }
    else
    {
        echo 'No Tienes Permiso Para Exportar Este Voucher';
    }
  }
  ob_end_flush();
?>