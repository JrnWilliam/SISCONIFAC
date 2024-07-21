var tablaventas

function InicarVenta()
{

}

function MostrarFormularioVenta(valor)
{
    LimpiarCampos()
    if(valor)
    {
        $("#TablaVenta").hide()
        $("#FormularioVenta").show()
        $("#BtnAgregar").hide()
    }
    else
    {
        $("#TableVenta").show()
        $("#FormularioVenta").hide()
        $("#BtnAgregar").show()
    }
}

function LimpiarCampos()
{
    $("#idventa").val("")
    $("#idcliente").val('').selectpicker('refresh')
    $("#tipocomprobante").val('').selectpicker('refresh')
    $("#seriecomprobante").val("")
    $("#numcomprobante").val("")
    $("#impuesto").val("")
}

InicarVenta()