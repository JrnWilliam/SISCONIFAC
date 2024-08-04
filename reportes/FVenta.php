<?php
  ob_start();
  session_start();
  
  if(!isset($_SESSION["nombre"]))
  {
    echo "Ingrese al Sistema Para Poder Generar Esta Factura";
  }
  else
  {
    if($_SESSION['ventas']==1)
    {
        require 'MFactura.php';

        $logo = "Logo.jpg";
        $extension = "jpg";
        $empresa = "Ferreteria Mi Bendición S.A";
        $ruc = "J0310000002118";
        $direccion = "León, Nicaragua";
        $telefono = "+505 2222-2222";
        $email = "fmibendicion@ejemplo.com";

        require_once '../modelos/CVentas.php';
        $ObjVentas = new CVentas();

        $respuestav = $ObjVentas-> CabeceraFacVenta($_GET["id"]);

        $registrov = $respuestav -> fetch_object();

        $pdf = new PDF_Invoice('P','mm','A4');
        $pdf->AddPage();

        $pdf -> addSociete(
            mb_convert_encoding($empresa, 'ISO-8859-1'),
            $ruc."\n".
            mb_convert_encoding("Dirección: ", 'ISO-8859-1').mb_convert_encoding($direccion, 'ISO-8859-1')."\n".
            mb_convert_encoding("Teléfono: ", 'ISO-8859-1').$telefono."\n".
            "email: ".$email,
            $logo,
            $extension
        );

        $pdf->fact_dev(
            "$registrov->tipo_comprobante"." ",
            "$registrov->serie_comprobante",
            "$registrov->num_comprobante"
        );

        $pdf->temporaire("");
        $pdf->addDate($registrov->fecha);

        $pdf->addClientAdresse(
            mb_convert_encoding($registrov->cliente, 'ISO-8859-1'),
            mb_convert_encoding("Dirección: ", 'ISO-8859-1').mb_convert_encoding($registrov->direccion, 'ISO-8859-1'),
            mb_convert_encoding($registrov->tipo_documento, 'ISO-8859-1').": ".
            $registrov->num_documento,
            "Email: ".$registrov->email,
            "Telefono: ".$registrov->telefono
        );

        $cols = array(
            mb_convert_encoding("Código", 'ISO-8859-1')=>23,
            mb_convert_encoding("Descripción", 'ISO-8859-1')=>78,
            "Cantidad"=>22,
            "P.U"=>25,
            "Descuento"=>20,
            "Subtotal"=>22    
        );

        $pdf->addCols( $cols);

        $cols = array(
            mb_convert_encoding("Código", 'ISO-8859-1')=>"L",
            mb_convert_encoding("Descripción", 'ISO-8859-1')=>"L",
            "Cantidad"=>"C",
            "P.U"=>"R",
            "Descuento"=>"R",
            "Subtotal"=>"C");

        $pdf->addLineFormat( $cols);
        $pdf->addLineFormat($cols);

        $y = 89;

        $respuestad = $ObjVentas -> DetalleFacturaVenta($_GET["id"]);
        
        while($registrod = $respuestad->fetch_object())
        {
            $line = array(
                mb_convert_encoding("Código", 'ISO-8859-1')=>"$registrod->codigo",
                mb_convert_encoding("Descripción", 'ISO-8859-1')=>mb_convert_encoding("$registrod->articulo", 'ISO-8859-1'),
                "Cantidad"=>"$registrod->cantidad",
                "P.U"=>"$registrod->precio_venta",
                "Descuento"=>"$registrod->descuento",
                "Subtotal"=>number_format("$registrod->subtotal", 2, '.', '')
            );
            $size = $pdf->addLine($y,$line);
            $y += $size + 2;
        }

        require_once 'Letras.php';
        $V = new EnLetras();
        $con_letra = strtoupper(
            $V->ValorEnLetras($registrov->total_venta+($registrov->total_venta*($registrov->impuesto/100)), "Cordobas Netos")
        );
        $pdf->addCadreTVAs("---".$con_letra);
        $pdf->addTVAs($registrov->impuesto,$registrov->total_venta, "C$ ");
        $pdf->addCadreEurosFrancs("IVA"."$registrov->impuesto %");
        $pdf->Output("Factura de Venta","I");
    }
    else
    {
        echo "No Tiene Permisos Para Exportar Esta Factura";
    }
  }
  ob_end_flush();
?>