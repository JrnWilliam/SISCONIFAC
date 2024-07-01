var tablausuario

function IniciarUsuarios()
{
    MostrarFormularioUsuario(false)
    ListarRegistrosUsuarios()

    $("#FormularioRegistroUsuario").on("submit", function(e){
        GuardaryEditarUsuarios(e)
        LimpiarCamposUsuarios()
    })

    $.post("../ajax/Usuario.php?operacion=Permisos&iduser=",function(r){
        $("#permisos").html(r)
    })
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
    $("#cargo").val("")
    $("#login").val("")
    $("#clave").val("")
    $("#imagen").wrap('<form>').closest('form').get(0).reset()
    $("#imagen").unwrap()
    $("#imagenactual").val("")
    $("#imgactual").attr("src","")
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
        $("#imgactual").hide()
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
        "iDisplayLength": 10,
        "order": [[0,"asc"]]
    }).DataTable()
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

function SeleccionarRegistroUsuario(idusuario)
{
    $.post("../ajax/Usuario.php?operacion=SeleccionarUsuario",
        {
            idusuario : idusuario
        },
        function(data, status)
        {
            data = JSON.parse(data)
            MostrarFormularioUsuario(true)
            if(data.imagen.trim() !== "")
            {
                $("#imgactual").show()
            }
            else
            {
                $("#imgactual").hide()
            }
            $("#idusuario").val(data.idusuario)
            $("#nombre").val(data.nombre)
            $("#tipodocumento").val(data.tipo_documento)
            $("#tipodocumento").selectpicker('refresh')
            $("#numdocumento").val(data.num_documento)
            $("#direccion").val(data.direccion)
            $("#telefono").val(data.telefono)
            $("#email").val(data.email)
            $("#cargo").val(data.cargo)
            $("#login").val(data.login)
            $("#clave").val(data.clave)
            $("#imagenactual").val(data.imagen)
            $("#imgactual").attr("src","../files/usuarios/" + data.imagen)
        })
        $.post("../ajax/Usuario.php?operacion=Permisos&iduser="+idusuario,function(r){
            $("#permisos").html(r)
        })
}

function DesactivarUsuario(idusuario)
{
    bootbox.confirm("¿Esta Seguro que Desea Desactivar Este Usuario?",
        function(result)
        {
            if(result)
            {
                $.post("../ajax/Usuario.php?operacion=DesactivarUsuario",
                    {
                        idusuario : idusuario
                    },
                    function(e)
                    {
                        bootbox.alert(e)
                        tablausuario.ajax.reload()
                    })
            }
        })
}

function ActivarUsuario(idusuario)
{
    bootbox.confirm("¿Esta Seguro que Desea Activar Este Usuario?",
        function(result)
        {
            if(result)
            {
                $.post("../ajax/Usuario.php?operacion=ActivarUsuario",
                    {
                        idusuario : idusuario
                    },
                    function(e)
                    {
                        bootbox.alert(e)
                        tablausuario.ajax.reload()
                    })
            }
        })
}

IniciarUsuarios()