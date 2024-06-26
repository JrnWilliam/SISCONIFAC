var tablausuario

function IniciarUsuarios()
{
    MostrarFormularioUsuario(false)
}

function LimpiarCamposUsuarios()
{
    $("#idusuario").val("")
    $("#nombre").val("")
    $("#tipodocumento").val("").selectpicker("refresh")
    $("#numdocumento").val("")
    $("#direccion").val("")
    $("#telefono").val("")
    $("#email").val("")
    // $
    // $
    // $
    // $
    // $
}

function MostrarFormularioUsuario(valor)
{
    LimpiarCamposUsuarios()
    if(valor)
    {
        $("#TablaUsuario").hide()
        $("#FormularioUsuario").show()
        $("#BtnGuardar").prop("disabled",false)
        $("#BtnAgregar").hide()
    }
    else
    {
        $("#TablaUsuario").show()
        $("#FormularioUsuario").hide()
        $("#BtnAgregar").show()
    }
}

IniciarUsuarios()