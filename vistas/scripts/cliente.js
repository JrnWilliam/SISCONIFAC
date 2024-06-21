var tablacliente
//Funcion Inicial que se ejecuta al cargar el script, ocultando el formulario, listando los proveedores y añadiendo un evento para el formulario.
function IniciarClientes()
{
    MostrarFormularioClientes(false)
    ListarRegistrosClientes()

    $("#FormularioRegistroClientes").on("submit", function(e){
        GuardaryEditarClientes(e)
    })
}
//Funcion que se encargara de limpiar los campos del formulario
function LimpiarCamposClientes()
{
    $("#idpersona").val("")
    $("#nombre").val("")
    $("#tipodocumento").val("").selectpicker("refresh")
    $("#numdocumento").val("")
    $("#direccion").val("")
    $("#telefono").val("")
    $("#email").val("")
}

function MostrarFormularioClientes(valor)
{
    LimpiarCamposClientes()
    if(valor)
    {
        $("#TablaClientes").hide()
        $("#FormularioClientes").show();
        $("#BtnGuardar").prop("disabled",false)
        $("#BtnAgregar").hide();
    }
    else
    {
        $("#TablaClientes").show()
        $("#FormularioClientes").hide();
        $("#BtnAgregar").show();
    }
}

function CerrarFormularioClientes()
{
    LimpiarCamposClientes()
    MostrarFormularioClientes(false)
}

function ListarRegistrosClientes()
{
    tablacliente = $("#TablaListadoClientes").dataTable({
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
            url: "../ajax/Persona.php?operacion=MostrarClientes",
            type: "get",
            dataType: "json",
            error: function(e)
            {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLenght":10,
        "order": [[0,"asc"]]
    }).DataTable()
}

function GuardaryEditarClientes(e)
{
    e.preventDefault()
    $("#BtnGuardar").prop("disabled",true)
    var formData = new FormData($("#FormularioRegistroClientes")[0])

    $.ajax({
        url: "../ajax/Persona.php?operacion=EditaryGuardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            bootbox.alert(datos)
            MostrarFormularioClientes(false)
            tablacliente.ajax.reload()
        }
    })
    LimpiarCamposClientes()
}

function SeleccionarRegistroCliente(idpersona)
{
    $.post("../ajax/Persona.php?operacion=Seleccionar", {idpersona : idpersona}, function(data,status){
        data = JSON.parse(data)
        MostrarFormularioClientes(true)
        $("#idpersona").val(data.idpersona)
        $("#nombre").val(data.nombre)
        $("#tipodocumento").val(data.tipo_documento)
        $("#tipodocumento").selectpicker('refresh')
        $("#numdocumento").val(data.num_documento)
        $("#direccion").val(data.direccion)
        $("#telefono").val(data.telefono)
        $("#email").val(data.email)
    })
}

function EliminarRegistroCliente(idpersona)
{
    bootbox.confirm("Esta Seguro Que Desea Eliminar Este Cliente", function(result){
        if(result)
        {
            $.post("../ajax/Persona.php?operacion=Eliminar", {idpersona : idpersona}, function(e){
                bootbox.alert(e)
                tablacliente.ajax.reload()
            })
        }
    })
}
IniciarClientes()