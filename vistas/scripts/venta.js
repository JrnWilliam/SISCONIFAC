var tablaventas
var impuesto = 15
var contador = 0
var detalle = 0

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
        $("#TablaVenta").show()
        $("#FormularioVenta").hide()
        $("#BtnAgregar").show()
    }
}

function CerrarFormularioVenta()
{
    LimpiarCampos()
    MostrarFormularioVenta(false)
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

function AgregarDetalleVenta(idarticulo,articulo,precioventa)
{
    var cantidad = 1
    var descuento = 0

    if(idarticulo!="")
    {
        var subtotal = cantidad * precioventa
        var fila = '<tr class="filas" id="fila'+contador+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="EliminarDetalleVenta('+contador+')"><i class="fa fa-close"></i></button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
        '<td><input type="number" name="cantidad[]" id="cantidad'+contador+'" value"'+cantidad+'"></td>'+
        '<td><input type="number" name="precioventa[]" id="precioventa'+contador+'" value="'+precioventa+'"></td>'+
        '<td><input type="number" name="descuento[]" value="'+descuento+'"></td>'+
        '<td><span name="subtotal" id="subtotal'+contador+'">'+subtotal+'</span></td>'+
        '</tr>'
        contador++
        detalle++
        $("#TablaDetallesVenta").append(fila)
        $("#cantidad"+(contador-1)).on('input',ModificarSubtotales)
        $("#precioventa"+(contador-1)).on('input',ModificarSubtotales)
        ModificarSubtotales()
    }
    else
    {
        bootbox.alert("Error al Ingresar el Detalle, Revisar los Datos del Artículo")
    }
}

function EliminarDetalleVenta(indice)
{
    $("#fila"+indice).remove()
    CalcularTotales()
    detalle-=1
}

function ModificarSubtotales()
{
    var cant = document.getElementsByName("cantidad[]")
    var price = document.getElementsByName("precioventa[]")
    var desc = document.getElementsByName("descuento[]")
    var subttl = document.getElementsByName("subtotal")

    for(var i = 0; i< cant.length; i++)
    {
        var inccant = cant[i]
        var incprice = price[i]
        var incdesc = desc[i]
        var incsubttl = subttl[i]

        incsubttl.value = (inccant.value * incprice.value) - incdesc.value
        document.getElementsByName("subtotal")[i].innerHTML = incsubttl.value
    }
    CalcularTotales()
}

function CargarArticulos()
{
    tablaventas = $("#TablaListadoArticulos").dataTable(
        {
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            buttons:[],
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
    ).DataTable()
}

function SeleccionarRegistroVenta(idventa)
{
    $.post("../ajax/Venta.php?Operacion=SeleccionarRegistroVentas",
        {
            idventa:idventa
        },
        function(data,status)
        {
            data = JSON.parse(data)
            MostrarFormularioVenta(true)

            $("#idventa").val(data.idventa)
            $("#idcliente").val(data.idcliente)
            $("#idcliente").selectpicker('refresh')
            $("#tipocomprobante").val(data.tipo_comprobante)
            $("#tipocomprobante").selectpicker('refres')
            $("#fechahora").val(data.fecha)
            $("#seriecomprobante").val(data.serie_comprobante)
            $("#numcomprobante").val(data.num_comprobante)
            $("#impuesto").val(data.impuesto)

            $.post("../ajax/Venta.php?Operacion=SeleccionarDetalleVenta&id="+idventa,
                function(r)
                {
                    $("#TablaDetallesVenta").html(r)
                    ModificarSubtotales()
                    detalle = document.getElementsByClassName("filas")
                }
            )
        })
        ActBtnGuardarEdit()
}

function ActBtnGuardarEdit()
{
    $("#idproveedor, #tipocomprobante, #fechahora, #seriecomprobante,#numcomprobante,#impuesto").change(function()
        {
            $("#BtnGuardar").show()
        })
}

IniciarVenta()