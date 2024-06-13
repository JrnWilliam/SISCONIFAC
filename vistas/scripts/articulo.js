var tablaarticulo

//Funcion Inicial
function IniciarArticulos()
{
    MostrarFormulario(false)
    ListarRegistros()

    $("#Formulario").on("submit", function(e)
    {
        GuardarRegistroArticulo(e)
        LimpiarCampos()
    })

    //Cargamos los items al select Categoria
    $.post("../ajax/Articulo.php?operacion=SeleccionarCatArticulo", function(r){
        $("#idcategoria").html(r);
        $('#idcategoria').selectpicker('refresh');
    })
}

//Funcion que se encargara de limpiar los campos
function LimpiarCampos()
{
    $("#idarticulo").val("")
    $("#codigo").val("")
    $("#nombre").val("")
    $("#descripcion").val("")
    $("#stock").val("")
    $("#imagen").wrap('<form>').closest('form').get(0).reset()
    $("#imagen").unwrap()
    $("#idcategoria").val('').selectpicker('refresh')
    $("#imagen_actual").val("")
    $("#img_actual").attr("src", "")
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
        $("#BtnAgregar").hide()
        $("#img_actual").hide()
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
    var formData = new FormData($("#Formulario")[0])

    $.ajax({
        url: "../ajax/Articulo.php?operacion=EditaryGuardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
            {
                bootbox.alert(datos)
                MostrarFormulario(false)
                tablaarticulo.ajax.reload()
            }
        })
        LimpiarCampos()
}

//Funcion encargada de seleccionar un registro de la tabla de articulos para su edicion
function SeleccionarRegistroArticulo(idarticulo)
{
    $.post("../ajax/Articulo.php?operacion=Seleccionar",{idarticulo : idarticulo},function(data,status){
        data = JSON.parse(data)
        MostrarFormulario(true)
        $("#img_actual").show()

        $("#idcategoria").val(data.idcategoria).selectpicker('refresh')
        $("#codigo").val(data.codigo)
        $("#nombre").val(data.nombre)
        $("#stock").val(data.stock)
        $("#descripcion").val(data.descripcion)
        $("#idarticulo").val(data.idarticulo)
        $("#imagen_actual").val(data.imagen)
        $("#img_actual").attr("src", "../files/articulos/" + data.imagen)
        GenerarCodBarra()
    })
}

//Funcion para desactivar determinado articulo
function DesactivarArticulo(idarticulo)
{
    bootbox.confirm("¿Esta Seguro que Desea Desactivar el Articulo?", function(result){
    if(result)
    {
        $.post("../ajax/Articulo.php?operacion=Desactivar",{idarticulo:idarticulo},function(e)
        {
            bootbox.alert(e)
            tablaarticulo.ajax.reload()
        })
    }    
})
}

//Funcion para activar determinado articulo
function ActivarArticulo(idarticulo)
{
    bootbox.confirm("¿Esta Seguro de Activar el Articulo?", function(result)
    {
        if(result)
            {
                $.post("../ajax/Articulo.php?operacion=Activar", {idarticulo:idarticulo}, function(e)
                {
                    bootbox.alert(e)
                    tablaarticulo.ajax.reload()
                })
            }
    })
}

function GenerarCodBarra()
{
    codigo = $("#codigo").val()
    JsBarcode("#barcode", codigo)
}

IniciarArticulos()