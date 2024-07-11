var tablaingresos

function IniciarIngresos()
{
    MostrarFormularioIngreso(false)
    ListarRegistrosIngreso()

    $("#FormularioRegistroIngresos").on("submit",function(e)
    {
        GuardarRegistroIngreso(e)
    })
}

function LimpiarCampos()
{
    $("#idproveedor").val("")
    $("#proveedor").val("")
    $("#seriecomprobante").val("")
    $("#numcomprobante").val("")
    $("#fechahora").val("")
    $("#impuesto").val("")
}

function MostrarFormularioIngreso(valor)
{

}

function ListarRegistrosIngreso()
{

}

IniciarIngresos()