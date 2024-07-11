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
    LimpiarCampos()
    if(valor)
    {
        $("#TablaListadoIngresos").hide()
        $("#FormularioIngreso").show()
        $("#BtnGuardar").prop("disabled",false)
        $("#BtnAgregar").hide()
    }
    else
    {
        $("#TablaListadoIngresos").show()
        $("#FormularioIngreso").hide()
        $("#BtnAgregar").show()
    }
}

function ListarRegistrosIngreso()
{

}

IniciarIngresos()