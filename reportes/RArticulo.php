<?php
ob_start();
session_start();

if(!isset($_SESSION['nombre']))
{
    echo "Ingrese al Sistema Para Visualizar Este Reporte";
}
else
{
    if($_SESSION['almacen']==1)
    {
        require('PDF_MC_Table.php');

        $pdf = new PDF_MC_Table();

        $pdf -> AddPage();

        $y_axis_initial = 25;

        $pdf -> SetFont('Arial','B',12);

        $pdf->Cell(40,6,'',0,0,'C');
        $pdf->Cell(100,6,'Lista de Articulos',1,0,'C');
        $pdf->Ln(10);

        $pdf->SetFillColor(232,232,232);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(58,6,'Nombre',1,0,'C',1);
        $pdf->Cell(50,6,utf8_decode('Categoría'),1,0,'C',1);
        $pdf->Cell(30,6,utf8_decode('Código'),1,0,'C',1);
        $pdf->Cell(12,6,'Stock',1,0,'C',1);
        $pdf->Cell(35,6,utf8_decode('Descripción'),1,0,'C',1);
        $pdf->Ln(10);

        require_once '../modelos/CArticulo.php';

        $Objarticulo = new  CArticulo();

        $respuesta = $Objarticulo->MostrarArticulo();

        $pdf->SetWidths(array(58,50,30,12,35));

        while($registro = $respuesta->fetch_object())
        {
            $nombre = $registro->nombre;
            $categoria = $registro->categoria;
            $codigo = $registro->codigo;
            $stock = $registro->stock;
            $descripcion = $registro->descripcion;

            $pdf->SetFont('Arial','',10);
            $pdf->Row(array(utf8_decode($nombre),utf8_decode($categoria),$codigo,$stock,utf8_decode($descripcion)));

            $pdf->Output();
        }

    }
    else
    {
        echo "No Tiene Permisos Para Visualizar Este Reporte";
    }
}
ob_end_flush();  
?>