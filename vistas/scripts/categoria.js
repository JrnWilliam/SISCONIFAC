var tabla

//Funcion Inicial
function Inicial()
{
    MostrarFormulario(false)
    ListarElementos()

    $("#Formulario").on("submit", function(e){
        GuardarRegistro(e)
    })
}

//Funcion que se encarga de limpiar los campos
function LimpiarCampos()
{
    $("#idcategoria").val("")
    $("#nombre").val("")
    $("#descripcion").val("")
}

//Funcion que nos mostrara el formulario al momento que vallamos a ingresar una categoria o la vallamos a editar
function MostrarFormulario(valor)
{
    LimpiarCampos()
    if (valor)
    {
        $("#listado_registros").hide()
        $("#formulario_registros").show()
        $("#BtnGuardar").prop("disabled",false)
        $("#BtnAgregar").hide()  
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

//Funcion que mostrara en lista los valores guardados en la tabla categoria
function ListarElementos()
{
    tabla=$('#TablaListado').dataTable(
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
                url: '../ajax/Categoria.php?operacion=Listar',
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
        }).DataTable()
}

//Funcion que se encargara de guardar o editar los registros
function GuardarRegistro(e)
{
    e.preventDefault()
    $("#BtnGuardar").prop("disabled",true)
    var formData = new FormData($("#Formulario")[0])

    $.ajax({
        url: "../ajax/Categoria.php?operacion=EditaryGuardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            bootbox.alert(datos)
            MostrarFormulario(false)
            tabla.ajax.reload()
        }
    })
    LimpiarCampos()
}

function SeleccionarRegistro(idcategoria)
{
    $.post("../ajax/Categoria.php?operacion=Seleccionar",{idcategoria:idcategoria}, function(data,status)
    {
        data = JSON.parse(data)
        MostrarFormulario(true)

        $("#nombre").val(data.nombre)
        $("#descripcion").val(data.descripcion)
        $("#idcategoria").val(data.idcategoria)
    })
}

//Funcion para desactivar las categorias
function DesactivarCategorias(idcategoria)
{
    bootbox.confirm("¿Está seguro de desactivar la Categoria?", function(result)
    {
        if (result)
            {
                $.post("../ajax/Categoria.php?operacion=Desactivar", {idcategoria:idcategoria}, function(e)
                {
                    bootbox.alert(e)
                    tabla.ajax.reload()
                })
            }
    })
}

//Funcion para activar las categorias
function ActivarCategorias(idcategoria)
{
    bootbox.confirm("¿Esta Seguro de Activar la Categoria?", function(result)
    {
        if(result)
            {
                $.post("../ajax/Categoria.php?operacion=Activar", {idcategoria:idcategoria}, function(e)
                {
                    bootbox.alert(e)
                    tabla.ajax.reload()
                })
            }
    })
}

Inicial()