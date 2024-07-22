var tablaventas

function IniciarVenta()
{
    MostrarFormularioVenta(false)
    MostrarVentas()

    $("#FormularioRegistroVenta").on("submit",function(e)
    {
        GuardarEditarVenta(e)
    })

    $.post("../ajax/Venta.php?Operacion=SeleccionarCliente",function (r)
    {
        $("#idcliente").html(r)
        $('#idcliente').selectpicker('refresh')  
    })
}

function MostrarFormularioVenta(valor)
{
    LimpiarCampos()
    if(valor)
    {
        $("#impuesto").val(0)
        $("#TablaVenta").hide()
        $("#FormularioVenta").show()
        $("#BtnAgregar").hide()
        CargarArticulos()
    }
    else
    {
        $("#TableVenta").show()
        $("#FormularioVenta").hide()
        $("#BtnAgregar").show()
    }
}

function LimpiarCampos()
{
    $("#idventa").val("")
    $("#idcliente").val('').selectpicker('refresh')
    $("#tipocomprobante").val('').selectpicker('refresh')
    $("#seriecomprobante").val("")
    $("#numcomprobante").val("")
    $("#impuesto").val("")
    $("#totalventa").val("")
    $(".filas").remove()
    $("#total").html(0)
    $("#BtnGuardar").hide()

    var ahora = new Date()
    var dia = ("0" + ahora.getDate()).slice(-2)
    var mes = ("0" + (ahora.getMonth()+1)).slice(-2)
    var hoy = ahora.getFullYear() + "-" + (mes) + "-" + (dia)
    $('#fechahora').val(hoy)
}

function MostrarVentas()
{
    tablaventas = $('#TablaListadoVenta').dataTable(
        {
            "lengthMenu": [5,10,15,20,25,30],
            "aProcessing": true,
            "aServerSide": true,
            dom: '<Bl<f>rtip>',
            buttons: [
                'copyHtml5',
		        'excelHtml5',
		        'csvHtml5',
		        'pdf'
            ],
            "ajax":
            {
                url: '../ajax/Venta.php?Operacion=MostrarVentas',
                type: "get",
                dataType: "json",
                error: function(e)
                {
                    console.log(e.responseText)
                }
            },
            "language":
            {
                "lengthMenu": "Mostrar : _MENU_ Registros"
            },
            "bDestroy": true,
            "iDisplayLength": 5,
            "order": [[0,"desc"]]
        }
    ).DataTable()
}

function GuardarEditarVenta(e)
{
    e.preventDefault()
    var formdata = FormData($("#FormularioRegistroVenta")[0]);

    $.ajax(
        {
            url: "../ajax/Venta.php?Operacion=GuardarEditar",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(datos)
            {
                bootbox.alert(datos)
                MostrarFormularioVenta(false)
                MostrarVentas()
            }
        }
    )
    LimpiarCampos()
}

function CargarArticulos()
{
    tablaventas = $("#TablaListadoArticulos").dataTable(
        {
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            buttons:['copyHtml5','excelHtml5','csvHtml5','pdf'],
            "ajax":
            {
                url: "../ajax/Venta.php?Operacion=MostrarArticulos",
                type: "get",
                dataType: "json",
                error: function (e)
                {
                    console.log(e.responseText)
                }
            },
            "bDestroy":true,
            "iDisplayLength":10,
            "order":[[0,"desc"]]
        }
    )
}
IniciarVenta()