var tablaingresos
var impuesto = 18
var contador= 0
var detalle = 0

function IniciarIngresos()
{
    MostrarFormularioIngreso(false)
    ListarRegistrosIngreso()
    $("#BtnAuxiliar").hide()

    $("#FormularioRegistroIngreso").on("submit",function(e)
    {
        GuardarRegistroIngreso(e)
    })

    $.post("../ajax/Ingreso.php?Operacion=SeleccionarProveedor",
        function(r)
        {
            $("#idproveedor").html(r)
            $("#idproveedor").selectpicker('refresh');
        }
    )
}

function LimpiarCampos()
{
    $("#idproveedor").val("")
    $("#proveedor").val("")
    $("#seriecomprobante").val("")
    $("#numcomprobante").val("")
    $("#fechahora").val("")
    $("#impuesto").val("")
    $("#idproveedor").val('').selectpicker('refresh')
}

function MostrarFormularioIngreso(valor)
{
    LimpiarCampos()
    if(valor)
    {
        $("#TablaIngresos").hide()
        $("#FormularioIngreso").show()
        $("#BtnGuardar").prop("disabled",false)
        $("#BtnAgregar").hide()
        ListarRegistrosArticulos()
    }
    else
    {
        $("#TablaIngresos").show()
        $("#FormularioIngreso").hide()
        $("#BtnAgregar").show()
    }
}

function CerrarFormularioIngreso()
{
    LimpiarCampos()
    MostrarFormularioIngreso(false)
}

function ListarRegistrosIngreso()
{
    tablaingresos = $("#TablaListadoIngreso").dataTable(
        {
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            buttons:
            [
                'copyHtml5','excelHtml5','csvHtml5','pdf'
            ],
            "ajax":
            {
                url: "../ajax/Ingreso.php?Operacion=MostrarIngresos",
                type: "get",
                dataType: "json",
                error: function(e)
                {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 10,
            "order": [[0, "desc"]]
        }
    ).DataTable();
}

function GuardaryEditarIngresos(e)
{
    e.prevenDefault()
    $("#BtnGuardar").prop("disabled",true)
    var formData = new FormData($("#FormularioRegistroIngreso")[0])

    $.ajax(
        {
            url: "../ajax/Ingreso.php?Operacion=GuardaryEditar",
            type: "POST",
            data: formData,
            ContentType: false,
            processData: false,
            success: function(datos)
            {
                bootbox.alert(datos)
                MostrarFormularioIngreso(false)
                tablaingresos.ajax.reload()
            }
        }
    )
    LimpiarCampos()
}

function SeleccionarRegistroIngreso(idingreso)
{
    $.post("../ajax/Ingreso.php?Operacion=SeleccionarRegistroIngreso", {idingreso:idingreso}, function(data,status){
        data = JSON.parse(data)
        MostrarFormularioIngreso()

        $("#idingreso").val(data.idcategoria)
        $("#idproveedor").val(data.idproveedor)
        $("#idusuario").val(data.idusuario)
    })
}

function AnularIngreso(idingreso)
{
    bootbox.confirm("Desea Anular Este Ingreso",
        function(result)
    {
        if(result)
        {
            $.post("..post/ajax/Ingreso.php?Operacion=AnularIngreso",
                {
                    idingreso:idingreso
                },
                function(e)
                {
                    bootbox.alert(e)
                    tablaingresos.ajax.reload()
                })
        }
    })
}

function ListarRegistrosArticulos()
{
    tablaingresos = $("#TablaListadoArticulos").dataTable(
        {
            "aProcessing":true,
            "aServerSide":true,
            dom: 'Bfrtip',
            buttons:['copyHtml5','excelHtml5','csvHtml5','pdf'],
            "ajax":
            {
                url: "../ajax/Ingreso.php?Operacion=MostrarArticulos",
                type:"get",
                dataType:"json",
                error: function(e)
                {
                    console.log(e.responseText);
                }
            },
            "bDestroy":true,
            "iDisplayLength":10,
            "order":[[0,"desc"]]
        }
    )
}

IniciarIngresos()