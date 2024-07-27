var tablaconsultafecha

function IniciarConsultasF()
{
    MostrarConsultasFechas()
    $("#Finicio").change(MostrarConsultasFechas)
    $("#Ffin").change(MostrarConsultasFechas)
}

function MostrarConsultasFechas()
{
    var finicio = $("#Finicio").val()
    var ffin = $("#Ffin").val()

    tablaconsultafecha = $('#TablaConsultaFecha').dataTable(
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
                url: '../ajax/Consultas.php?Operacion=FiltroXFecha',
                data: {f_inicio : finicio,f_fin : ffin},
                type: "get",
                dataType: "json",
                error: function (e)
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
        }).DataTable()
}

IniciarConsultasF()