var tablaventas
var impuesto = 15
var contador = 0
var detalle = 0
$("#tipocomprobante").change(AgregarImpuestoVenta)

function IniciarVenta()
{
    MostrarFormularioVenta(false)
    MostrarVentas()
    $("#BtnGuardar").hide()

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
    HabilitarCamposVenta()
    if(valor)
    {
        $("#impuesto").val("0")
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
    $("#total").html("C$ 0.00")
    $("#BtnGuardar").hide()
    detalle = 0

    var ahora = new Date()
    var dia = ("0" + ahora.getDate()).slice(-2)
    var mes = ("0" + (ahora.getMonth()+1)).slice(-2)
    var hoy = ahora.getFullYear() + "-" + (mes) + "-" + (dia)
    $('#fechahora').val(hoy)
}

function DeshabilitarCamposVenta()
{
    $("#idcliente").prop("disabled",true)
    $("#fechahora").prop("disabled",true)
    $("#tipocomprobante").prop("disabled",true)
    $("#seriecomprobante").prop("disabled",true)
    $("#numcomprobante").prop("disabled",true)
    $("#impuesto").prop("disabled",true)
    $("#AgregarArticuloVenta").hide()
}

function HabilitarCamposVenta()
{
    $("#idcliente").prop("disabled",false)
    $("#fechahora").prop("disabled",false)
    $("#tipocomprobante").prop("disabled",false)
    $("#seriecomprobante").prop("disabled",false)
    $("#numcomprobante").prop("disabled",false)
    $("#impuesto").prop("disabled",false)
    $("#AgregarArticuloVenta").show()
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
            "iDisplayLength": 10,
            "order": [[0,"desc"]]
        }
    ).DataTable()
}

function AnularVenta(idventa)
{
    bootbox.confirm("¿Desea Anular Esta Venta?",
        function(result)
        {
            if(result)
            {
                $.post("../ajax/Venta.php?Operacion=AnularVenta",
                    {
                        idventa:idventa
                    },
                    function(e)
                    {
                        bootbox.alert(e)
                        MostrarVentas()
                    }
                )
            }
        }
    )
}

function GuardarEditarVenta(e)
{
    e.preventDefault()
    var formData = new FormData($("#FormularioRegistroVenta")[0]);

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
    var cantidad = $('#cantidadart' + idarticulo).val()
    var descuento = 0

    if(idarticulo!="" && cantidad>0)
    {
        var subtotal = cantidad * precioventa
        var fila = '<tr class="filas" id="fila'+contador+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="EliminarDetalleVenta('+contador+')"><i class="fa fa-close"></i></button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
        '<td><input type="number" name="cantidad[]" id="cantidad'+contador+'" value="'+cantidad+'"></td>'+
        '<td><input type="number" name="precioventa[]" id="precioventa'+contador+'" value="'+precioventa+'"></td>'+
        '<td><input type="number" name="descuento[]" id="descuento'+contador+'" value="'+descuento+'"></td>'+
        '<td><span name="subtotal" id="subtotal'+contador+'">'+subtotal+'</span></td>'+
        '</tr>'
        contador++
        detalle++
        $("#TablaDetallesVenta").append(fila)
        $("#cantidad"+(contador-1)).on('input',ModificarSubtotales)
        $("#precioventa"+(contador-1)).on('input',ModificarSubtotales)
        $("#descuento"+(contador-1)).on('input',ModificarSubtotales)
        ModificarSubtotales()
        $('#cantidadart'+idarticulo).val(1)
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

        var disc = parseFloat(incdesc.value)
        if(isNaN(disc) || disc<0)
        {
            disc = 0
        }
        else
        {
            disc = disc / 100
        }

        incsubttl.value = (inccant.value * incprice.value)-((inccant.value * incprice.value) * disc)
        document.getElementsByName("subtotal")[i].innerHTML = incsubttl.value.toFixed(2)
    }
    CalcularTotales()
    EvaluarVenta()
}

function CalcularTotales()
{
    var sub = document.getElementsByName("subtotal")
    var total = 0.0;

    Array.from(sub).forEach(function(elemento)
    {
        total += parseFloat(elemento.value)
    })
    var ttl = total.toLocaleString('es-NI',
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })

    $("#total").html("C$ " + ttl)
    $("#totalventa").val(total.toFixed(2))
    EvaluarVenta()    
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
            HabilitarCamposVenta()
            MostrarFormularioVenta(true)

            $("#idventa").val(data.idventa)
            $("#idcliente").val(data.idcliente)
            $("#idcliente").selectpicker('refresh')
            $("#tipocomprobante").val(data.tipo_comprobante)
            $("#tipocomprobante").selectpicker('refresh')
            $("#fechahora").val(data.fecha)
            $("#seriecomprobante").val(data.serie_comprobante)
            $("#numcomprobante").val(data.num_comprobante)
            $("#impuesto").val(data.impuesto)

            $.post("../ajax/Venta.php?Operacion=SeleccionarDetalleVentas&id="+idventa,
                function(r)
                {
                    $("#TablaDetallesVenta").html(r)
                    ModificarSubtotales()
                    detalle = document.getElementsByClassName("filas").length
                }
            )
        })
        ActBtnGuardarEdit()
}

function SeleccionarRegistroVentaAnulada(idventa)
{
    $.post("../ajax/Venta.php?Operacion=SeleccionarRegistroVentas",
        {
            idventa : idventa
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

            DeshabilitarCamposVenta()

            $.post("../ajax/Venta.php?Operacion=SeleccionarDetalleVentaAnulada&id="+idventa,
                function(r)
                {
                    $("#TablaDetallesVenta").html(r)
                })
        })
}

function ActBtnGuardarEdit()
{
    $("#idcliente, #tipocomprobante, #fechahora, #seriecomprobante,#numcomprobante,#impuesto").change(function()
        {
            $("#BtnGuardar").show()
        })
}

function EvaluarVenta()
{
    if(detalle>0)
    {
        $("#BtnGuardar").show()
    }
    else
    {
        $("#BtnGuardar").hide()
        contador=0
    }
}

function AgregarImpuestoVenta()
{
    var comprobante = $("#tipocomprobante option:selected").text()

    if(comprobante==='Factura')
    {
        $("#impuesto").val(impuesto)
    }
    else
    {
        $("#impuesto").val(0)
    }
}

IniciarVenta()