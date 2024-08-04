    var tablaingresos
    var impuesto = 15   
    var contador= 0
    var detalle = 0
    var tcomprobante = $("#tipocomprobante").val()
    var scomprobante = $("#seriecomprobante").val()
    $("#tipocomprobante").change(AgregarImpuesto)
    $("#tipocomprobante").change(GenerarSerieComprobante)

    function IniciarIngresos()
    {
        MostrarFormularioIngreso(false)
        ListarRegistrosIngreso()
        $("#BtnGuardar").hide()

        $("#seriecomprobante").prop("disabled",true)
        $("#numcomprobante").prop("disabled",true)

        $("#FormularioRegistroIngreso").on("submit",function(e)
        {
            GuardarRegistroIngreso(e)
        })

        AgregarImpuesto()

        $.post("../ajax/Ingreso.php?Operacion=SeleccionarProveedor",
            function(r)
            {
                $("#idproveedor").html(r)
                $("#idproveedor").selectpicker('refresh');
            }
        )

        GenerarSerieComprobante()
        scomprobante = $("#seriecomprobante").val()

        GenerarNumComprobante(tcomprobante, scomprobante).then(function (NuevoNumComprobante)
        {
            $("#numcomprobante").val(NuevoNumComprobante)
        })
    }

    function GenerarNumComprobante(tcomprobante,scomprobante)
    {
        return $.post("../ajax/Venta.php?Operacion=GenerarNumComprobante",
        {
            tipocomprobante: tcomprobante,
            seriecomprobante: scomprobante
        }
        ).then(function(response)
        {
            var result = JSON.parse(response)
            return result.nuevonum;
        })
    }

    function GenerarSerieComprobante()
    {
        var tipocomp = $("#tipocomprobante option:selected").text()
        if(tipocomp ==='Orden de Compra')
        {
            $("#seriecomprobante").val("O/C")
        }
        else
        {
            $("#seriecomprobante").val("V")
        }
}

    function LimpiarCampos()
    {
        $("#idingreso").val("")
        $("#tipocomprobante").val('').selectpicker('refresh')
        $("#idproveedor").val('').selectpicker('refresh')
        $("#totalcompra").val("")
        $(".filas").remove()
        $("#total").html("0")
        $("#BtnGuardar").hide()
        detalle=0;

        var ahora = new Date()
        var dia = ("0" + ahora.getDate()).slice(-2)
        var mes = ("0" + (ahora.getMonth()+1)).slice(-2)
        var hoy = ahora.getFullYear() + "-" + (mes) + "-" + (dia)
        $('#fechahora').val(hoy)
    }

    function DeshabilitarCampos()
    {
        $("#idproveedor").prop("disabled",true)
        $("#fechahora").prop("disabled",true)
        $("#tipocomprobante").prop("disabled",true)
        $("#impuesto").prop("disabled",true)
        $("#AgregarArticulo").hide()
    }

    function HabilitarCampos()
    {
        $("#idproveedor").prop("disabled",false)
        $("#fechahora").prop("disabled",false)
        $("#tipocomprobante").prop("disabled",false)
        $("#impuesto").prop("disabled",false)
        $("#AgregarArticulo").show()
    }

    function MostrarFormularioIngreso(valor)
    {
        HabilitarCampos()
        LimpiarCampos()
        if(valor)
        {
            $("#TablaIngreso").hide()
            $("#FormularioIngreso").show()
            $("#BtnAgregar").hide()
            ListarRegistrosArticulos()
        }
        else
        {
            $("#TablaIngreso").show()
            $("#FormularioIngreso").hide()
            $("#BtnAgregar").show()
        }
    }

    function CerrarFormularioIngreso()
    {
        LimpiarCampos()
        MostrarFormularioIngreso(false)
        AgregarImpuesto()
        GenerarSerieComprobante()
        GenerarNumComprobante(tcomprobante, scomprobante).then(function (NuevoNumComprobante)
        {
            $("#numcomprobante").val(NuevoNumComprobante)
        })
    }

    function ListarRegistrosIngreso()
    {
        tablaingresos = $('#TablaListadoIngreso').dataTable(
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
                    url: "../ajax/Ingreso.php?Operacion=MostrarIngresos",
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
                "order": [[0, "desc"]]
            }
        ).DataTable()
    }

    function GuardarRegistroIngreso(e)
    {
        e.preventDefault()
        var formData = new FormData($("#FormularioRegistroIngreso")[0])

        $.ajax(
            {
                url: "../ajax/Ingreso.php?Operacion=GuardaryEditar",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(datos)
                {
                    bootbox.alert(datos)
                    MostrarFormularioIngreso(false)
                    ListarRegistrosIngreso()
                }
            }
        )
        GenerarNumComprobante(tcomprobante, scomprobante).then(function (NuevoNumComprobante)
        {
            $("#numcomprobante").val(NuevoNumComprobante)
        })
        
        LimpiarCampos()
    }

    function MostrarRegistroIngreso(idingreso)
    {
        $.post("../ajax/Ingreso.php?Operacion=SeleccionarRegistroIngreso", {idingreso:idingreso}, function(data,status)
        {
            data = JSON.parse(data)
            HabilitarCampos()
            MostrarFormularioIngreso(true)

            $("#idingreso").val(data.idingreso)
            $("#idproveedor").val(data.idproveedor)
            $("#idproveedor").selectpicker('refresh')
            $("#fechahora").val(data.fecha)
            $("#tipocomprobante").val(data.tipo_comprobante)
            $("#tipocomprobante").selectpicker('refresh')
            $("#seriecomprobante").val(data.serie_comprobante)
            $("#numcomprobante").val(data.num_comprobante)
            $("#impuesto").val(data.impuesto)

            $.post("../ajax/Ingreso.php?Operacion=SeleccionarDetallesIngresos&id="+idingreso,
                function(r)
                {
                    $("#TablaDetalles").html(r)
                    ModificarSubtotales()
                    detalle = document.getElementsByClassName("filas").length
                }
            )
        })
        ActBtnGuardarEdit()
    }

    function MostrarRegistroIngresoAnulado(idingreso)
    {
        $.post("../ajax/Ingreso.php?Operacion=SeleccionarRegistroIngreso",
            {
                idingreso:idingreso
            },
            function(data,status)
            {
                data = JSON.parse(data)
                MostrarFormularioIngreso(true)

            $("#idingreso").val(data.idingreso)
            $("#idproveedor").val(data.idproveedor)
            $("#idproveedor").selectpicker('refresh')
            $("#fechahora").val(data.fecha)
            $("#tipocomprobante").val(data.tipo_comprobante)
            $("#tipocomprobante").selectpicker('refresh')
            $("#seriecomprobante").val(data.serie_comprobante)
            $("#numcomprobante").val(data.num_comprobante)
            $("#impuesto").val(data.impuesto)

            DeshabilitarCampos()

            $.post("../ajax/Ingreso.php?Operacion=SeleccionarDetallesIngresoAnulado&id="+idingreso,
                function(r)
                {
                    $("#TablaDetalles").html(r)
                })
            }
        )
    }

    function AnularIngreso(idingreso)
    {
        bootbox.confirm("Desea Anular Este Ingreso",
            function(result)
        {
            if(result)
            {
                $.post("../ajax/Ingreso.php?Operacion=AnularIngreso",
                    {
                        idingreso:idingreso
                    },
                    function(e)
                    {
                        bootbox.alert(e)
                        ListarRegistrosIngreso()
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
                buttons:[],
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
        ).DataTable()
    }

    function AgregarImpuesto()
    {
        var tcomprobante=$("#tipocomprobante option:selected").text()
        console.log(tcomprobante)
        if(tcomprobante==='Orden de Compra')
        {
            $("#impuesto").val(impuesto)
        }
        else
        {
            $("#impuesto").val(0)
        }
    }

    function AgregarDetalleCompra(idarticulo,articulo)
    {
        var cantidad = 1
        var preciocompra = 1
        var precioventa = 1

        if(idarticulo!="")
        {
            var subtotal = cantidad*preciocompra
            var fila = '<tr class="filas" id="fila'+contador+'">'+
            '<td><button type="button" class="btn btn-danger" onclick="EliminarCompra('+contador+')"><i class="fa fa-close"></i></button></td>'+
            '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
            '<td><input type="number" name="cantidad[]" id="cantidad'+contador+'" value="'+cantidad+'"></td>'+
            '<td><input type="number" name="preciocompra[]" id="preciocompra'+contador+'" value="'+preciocompra+'"></td>'+
            '<td><input type="number" name="precioventa[]" value="'+precioventa+'"></td>'+
            '<td><span name="subtotal" id="subtotal'+contador+'">'+subtotal+'</span></td>'+
            '</tr>'
            contador++
            detalle++
            $('#TablaDetalles').append(fila)
            $("#cantidad" +(contador-1)).on('input',ModificarSubtotales)
            $("#preciocompra"+(contador-1)).on('input',ModificarSubtotales)
            ModificarSubtotales()
        }
        else
        {
            bootbox.alert("Error al Ingresar el Detalle, Revisar los Datos del Artículo")
        }
    }

    function ModificarSubtotales()
    {
        var cant = document.getElementsByName("cantidad[]")
        var price = document.getElementsByName("preciocompra[]")
        var sub = document.getElementsByName("subtotal")

        for(var i = 0; i < cant.length; i++)
        {
            var inpcantidad = cant[i]
            var inpprecio = price[i]
            var inpsubtotal = sub[i]

            inpsubtotal.value=inpcantidad.value * inpprecio.value
            document.getElementsByName("subtotal")[i].innerHTML = inpsubtotal.value
        }
        CalcularTotales()
        EvaluarCompra()
    }

    function CalcularTotales()
    {
        var sub = document.getElementsByName("subtotal")
        var total = 0.0

        for(var i = 0; i < sub.length; i++)
        {
            total += document.getElementsByName("subtotal")[i].value
        }

        $("#total").html("C$ " + total)
        $("#totalcompra").val(total)
        EvaluarCompra()
    }

    function EvaluarCompra()
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

    function EliminarCompra(indice)
    {
        $("#fila"+indice).remove()
        CalcularTotales()
        detalle = detalle-1
    }

    function ActBtnGuardarEdit()
    {
        $("#idproveedor, #tipocomprobante, #fechahora, #seriecomprobante,#numcomprobante,#impuesto").change(function()
        {
            $("#BtnGuardar").show()
        })   
    }

    $("#tipocomprobante, #seriecomprobante").change(function()
    {
        var tcomprobante = $("#tipocomprobante").val()
        var scomprobante = $("#seriecomprobante").val()

        GenerarNumComprobante(tcomprobante, scomprobante).then(function(NuevoNumComprobante)
        {
            $("#numcomprobante").val(NuevoNumComprobante)
        })
    })

    IniciarIngresos()