var tablaarticulo

function IniciarArticulos()
{
    MostrarFormulario(false)
    ListarRegistros()

}

//Funcion que se encargara de limpiar los campos
function LimpiarCampos()
{
    $("#codigo").val("")
    $("#nombre").val("")
    $("#descripcion").val("")
    $("#stock").val("")
}
//Funcion que nos mostrara el formulario al momento que vallamos a ingresar un articulo o se requiera editar
function MostrarFormulario(valor)
{
    LimpiarCampos()
    if (valor)
    {
        $("#listado_registros").hide()
        $("#formulario_registros").show()
        $("#BtnGuardar").prop("disabled",false)
        $("BtnAgregar").hide()
    }
    else
    {
        $("#listado_registros").show()
        $("#formulario_registros").hide()
        $("#BtnAgregar").show()
    }
}

//Funcion que nos permitira ocultar o cerrar el formulario
function CerrarFormulario()
{
    LimpiarCampos()
    MostrarFormulario(false)
}

//Función que mostrara en lista los valores guardados en la tabla articulos
function ListarRegistros()
{
    tablaarticulo = $('#TablaListadoArticulos').dataTable(
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
                url: '../ajax/Articulo.php?operacion=Listar',
                type: "get",
                dataType: "json",
                error: function(e)
                {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 10,
            "order": [[0, "asc"]]
        }
    ).DataTable()
}

//Funcion encargada de guardar los articulos que se agreguen a la base de datos
function GuardarRegistroArticulo(e)
{
    e.preventDefault()
    $("#BtnGuardar").prop("disabled",true)
    var formData = new FormData($("#formulario")[0])

    $.ajax(
        {
            url: "../ajax/Articulo.php?operacion=EditaryGuardar",
            type: "POST",
            data: formData,
            contentType: false,
            proccessData: false,

            success: function(datos)
            {
                bootbox.alert(datos)
                MostrarFormulario(false)
                tablaarticulo.ajax.reload()
            }
        })
        LimpiarCampos()
}

IniciarArticulos()