var tablapermiso

function IniciarPermisos()
{
    MostrarFormularioPermiso(false)
}

function MostrarFormularioPermiso(valor)
{
    if(valor)
    {
        $("#TablaPermiso").hide()
        $("#FormularioPermiso").show()
        $("#BtnGuardar").prop("disabled",false)
        $("#BtnAgregar").hide()
    }
    else
    {
        $("#TablaPermiso").hide()
        $("#FormularioPermiso").show()
        $("#BtnAgregar").hide()
    }
}