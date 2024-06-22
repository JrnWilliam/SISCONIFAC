var tablapermiso

function IniciarPermisos()
{
    MostrarFormularioPermiso(false)
    ListarPermisos()
}

function MostrarFormularioPermiso(valor)
{
    if(valor)
    {
        $("#TablaPermiso").hide()
        $("#FormularioPermiso").show()
        $("#BtnGuardar").prop("disabled",false)
        $("#BtnAgregar").hide()
    }
    else
    {
        $("#TablaPermiso").hide()
        $("#FormularioPermiso").show()
        $("#BtnAgregar").hide()
    }
}

function ListarPermisos()
{
    tablapermiso = $("#TablaListadoPermisos").dataTable({
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
            url: "../ajax/Permiso.php?operacion=MostrarPermisos",
            type: "get",
            dataType: "json",
            error: function(e)
            {
                console.log(e.responseText)
            }
        },
        "bDestroy": true,
        "iDisplayLenght":10,
        "order": [[0,"asc"]]
    }).dataTable()
}

IniciarPermisos()