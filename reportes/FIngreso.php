<?php
    ob_start();
    session_start();

    if(!isset($_SESSION["nombre"]))
    {
        echo "Ingrese al Sistema Para Poder Generar Esta Factura";
    }
    else
    {
        if($_SESSION['compras']==1)
        {
            require 'MFactura.php';

            $logo = "Logo.jpg";
            $extension = "jpg";
            $empresa = "Ferreteria Mi Bendición S.A";
            $ruc = "J0310000002118";
            $direccion = "León, Nicaragua";
            $telefono = "+505 2222-2222";
            $email = "fmibendicion@ejemplo.com";

            require_once '../modelos/CIngresos.php';
            $Objingreso = new CIngresos();

            $respuestai = $Objingreso->CabeceraFacIngreso($_GET["id"]);
            $registroi = $respuestai->fetch_object();

            $pdf = new PDF_Invoice('P','mm','A4');
            $pdf->AddPage();

            $pdf -> addSociete(
                utf8_decode($empresa),
                $ruc."\n".
                utf8_decode("Dirección: ").utf8_decode($direccion)."\n".
                utf8_decode("Teléfono: ").$telefono."\n".
                "email: ".$email,
                $logo,
                $extension
            );

            $pdf->fact_dev(
                "$registroi->tipo_comprobante"." ",
                "$registroi->serie_comprobante",
                "$registroi->num_comprobante"
            );

            $pdf->temporaire("");
            $pdf->addDate($registroi->fecha);

            $pdf->addClientAdresse(
                utf8_decode($registroi->proveedor),
                utf8_decode("Direccion: ").utf8_decode($registroi->direccion),
                utf8_decode($registroi->tipo_documento).": ".$registroi->num_documento,
                "Email: ".$registroi->email,
                "Telefono: ".$registroi->telefono
            );

            $cols = array(
                utf8_decode("Código")=>23,
                utf8_decode("Descripción")=>78,
                "Cantidad"=>22,
                "P.C."=>25,
                "P.V."=>20,
                "SubTotal"=>22
            );

            $pdf->addCols( $cols);

            $cols = array(
                utf8_decode("Código")=>"L",
                utf8_decode("Descripción")=>"L",
                "Cantidad"=>"C",
                "P.C."=>"R",
                "P.V."=>"R",
                "SubTotal"=>"C"
            );

            $pdf->addLineFormat( $cols);
            $pdf->addLineFormat($cols);

            $y = 89;

            $respuestad = $Objingreso -> DetalleFacturaIngreso($_GET["id"]);

            while($registrod = $respuestad->fetch_object())
            {
                $line = array(
                    utf8_decode("Código")=>"$registrod->codigo",
                    utf8_decode("Descripción")=>utf8_decode("$registrod->articulo"),
                    "Cantidad"=>"$registrod->cantidad",
                    "P.C."=>"$registrod->precio_compra",
                    "P.V."=>"$registrod->precio_venta",
                    "SubTotal"=>number_format("$registrod->subtotal", 2, '.', '')
                );
                $size = $pdf->addLine($y,$line);
                $y += $size + 2;
            }

            require_once 'Letras.php';
            $C = new EnLetras();
            $aletras = strtoupper(
                $C->ValorEnLetras($registroi->total_compra+($registroi->total_compra*($registroi->impuesto/100)),"Cordobas Netos")
            );

            $pdf->addCadreTVAs("---".$aletras);
            $pdf->addTVAs($registroi->impuesto,$registroi->total_compra,"C$ ");
            $pdf->addCadreEurosFrancs("IVA"."$registroi->impuesto %");
            $pdf->Output("Orden de Compra","I");
        }
        else
        {
            echo "No Tiene Permisos Para Exportar Esta Factura";
        }
    }
    ob_end_flush();
?>