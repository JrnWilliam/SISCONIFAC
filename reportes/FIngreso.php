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
            
        }
        else
        {
            echo "No Tiene Permisos Para Exportar Esta Factura";
        }
    }
    ob_end_flush();
?>