var tablaconsultaventas

function IniciarConsultaVenta()
{
    MostrarConsultasVFechas()
    $.post("../ajax/Venta.php?Operacion=SeleccionarCliente",function (r)
    {
        $("#idcliente").html(r)
        $('#idcliente').selectpicker('refresh')
    })
}

function MostrarConsultasVFechas()
{
    var finicio = $("#Finicio").val()
    var ffin = $("#Ffin").val()
    var idcliente = $("#idcliente").val()

    tablaconsultaventas = $('#TablaConsultaVenta').dataTable(
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
                url: '../ajax/Consultas.php?Operacion=FiltroXFechaCliente',
                data: {f_inicio : finicio,f_fin : ffin, id : idcliente },
                type: "get",
                dataType: "json",
                error: function(e)
                {
                    console.log(e)
                }
            },
            "language":
            {
                "lengthMenu": "Mostrar : _MENU_ Registros"
            },
            "bDestroy": true,
            "iDisplayLength": 10,
            "order": [[0,"desc"]]
        }).DataTable()
}

IniciarConsultaVenta()