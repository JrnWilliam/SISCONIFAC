var tablaventas

function IniciarVenta()
{
    MostrarFormularioVenta(false)
    MostrarVentas()
    
}

function MostrarFormularioVenta(valor)
{
    LimpiarCampos()
    if(valor)
    {
        $("#TablaVenta").hide()
        $("#FormularioVenta").show()
        $("#BtnAgregar").hide()
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
}

function MostrarVentas()
{
    tablaventas = $('#TablaListadoVentas').dataTable(
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
                    console.log(e.resposeText)
                }
            },
            "language":
            {
                "lengthMenu": "Mostrar : _MENU_ registros"
            },
            "bDestroy": true,
            "iDisplayLength": 5,
            "order": [[0,"desc"]]
        }
    ).DataTable()
}

IniciarVenta()