var tablausuario

function IniciarUsuarios()
{
    MostrarFormularioUsuario(false)
    ListarRegistrosUsuarios()
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

function CerrarFormularioUsuario()
{
    LimpiarCamposUsuarios()
    MostrarFormularioUsuario(false)
}

function ListarRegistrosUsuarios()
{
    tablausuario = $("#TablaListadoUsuario").dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax":
        {
            url: "../ajax/Usuario.php?operacion=MostrarUsuarios",
            type: "get",
            dataType: "json",
            error: function(e)
            {
                console.log(e.responseText)
            }
        },
        "bDestroy": true,
        "iDisplayLenght": 10,
        "order": [[0,"asc"]]
    }).dataTable()
}

function GuardaryEditarUsuarios(e)
{
    e.preventDefault()
    $("#BtnGuardar").prop("disabled",true)
    var formData = new FormData($("#FormularioRegistroUsuario")[0])

    $.ajax({
        url: "../ajax/Usuario.php?operacion=EditaryGuardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            bootbox.alert(datos)
            MostrarFormularioUsuario(false)
            tablausuario.ajax.reload()
        }
    })
    LimpiarCamposUsuarios()
}

IniciarUsuarios()