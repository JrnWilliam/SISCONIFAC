var tablaproveedor

//Funcion Inicial que se ejecuta al cargar el script, ocultando el formulario, listando los proveedores y añadiendo un evento para el formulario.
function IniciarProveedores()
{
    MostrarFormularioProveedores(false)
    ListarRegistrosProveedores()

    $("#FormularioProveedores").on("submit", function(e){
        GuardaryEditarRegistrosProveedores(e)
    })
}
//Funcion que se encargara de limpiar los campos del formulario
function LimpiarCampos()
{
    $("#idpersona").val("")
    $("#nombre").val("")
    $("#tipodocumento").val("").selectpicker("refresh")
    $("#numdocumento").val("")
    $("#direccion").val("")
    $("#telefono").val("")
    $("#email").val("")
}

function MostrarFormularioProveedores(valor)
{
    LimpiarCampos()
    if(valor)
    {
        $("#TablaProveedores").hide()
        $("#FormularioRegistroProveedores").show()
        $("#BtnGuardar").prop("disabled", false)
        $("#BtnAgregar").hide()
    }
    else
    {
        $("#TablaProveedores").show()
        $("#FormularioRegistroProveedores").hide()
        $("#BtnAgregar").show()
    }
}

//Funcion que nos permitira ocultar o cerrar el formulario
function CerrarFormularioProveedores()
{
    LimpiarCampos()
    MostrarFormularioProveedores(false)
}
//Función que mostrara en lista los valores guardados en la tabla persona
//solamente de los proveedores
function ListarRegistrosProveedores()
{
    tablaproveedor = $('#TablaListadoProveedores').dataTable(
        {
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
                url: "../ajax/Persona.php?operacion=MostrarProveedores",
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
        }
    ).DataTable()
}
//Funcion que se encargara de manejar el envio del formulario para guardar o actualizar un proveedor, utilizando AJAX para enviar los datos
function GuardaryEditarRegistrosProveedores(e)
{
    e.preventDefault()
    $("#BtnGuardar").prop("disabled",true)
    var formData = new FormData($("#FormularioProveedores")[0])

    $.ajax({
        url: "../ajax/Persona.php?operacion=EditaryGuardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            bootbox.alert(datos)
            MostrarFormularioProveedores(false)
            tablaproveedor.ajax.reload()
        }
    })
    LimpiarCampos()
}
//Funcion que se encarga de seleccionar los registros de un articulo en especifico y llenar el formulario con estos datos para su edicion
function SeleccionarRegistroProveedor(idpersona)
{
    $.post("../ajax/Persona.php?operacion=Seleccionar", {idpersona : idpersona}, function(data,status){
        data = JSON.parse(data)

        MostrarFormularioProveedores(true)

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
//Funcion encargada de eliminar un proveedor en especifico
function EliminarRegistroProveedor(idpersona)
{
    bootbox.confirm("¿Esta Seguro Que Desea Eliminar Este Proveedor", function(result){
        if(result)
        {
            $.post("../ajax/Persona.php?operacion=Eliminar", {idpersona : idpersona}, function(e){
                bootbox.alert(e)
                tablaproveedor.ajax.reload()
            })
        }
    })
}
//Se invoca a la funcion inicial para que esta oculte al formulario y este al pendiente del siguiente envio de datos a travez del formulario
IniciarProveedores()