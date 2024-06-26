var tablausuario

function IniciarUsuarios()
{

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
}

IniciarUsuarios()