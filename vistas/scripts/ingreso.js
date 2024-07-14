    var tablaingresos;
    var impuesto = 15   
    var contador= 0
    var detalle = 0
    $("#tipocomprobante").change(AgregarImpuesto)

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
                        console.log(e.responseText)
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
        e.preventDefault()
        $("#BtnGuardar").prop("disabled",true)
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
            MostrarFormularioIngreso(true)

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
                $.post("../ajax/Ingreso.php?Operacion=AnularIngreso",
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

    function AgregarImpuesto()
    {
        var tcomprobante=$("#tipocomprobante option:selected").text()
        if(tcomprobante==='Factura')
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
            '<td><button type="button" class="btn btn-danger" onclick="EmilinarCompra('+contador+')">X</button></td>'+
            '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
            '<td><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
            '<td><input type="number" name="preciocompra[]" id="preciocompra[]" value="'+preciocompra+'"></td>'+
            '<td><input type="number" name="precioventa[]" value="'+precioventa+'"></td>'+
            '<td><span name="subtotal" id="subtotal'+contador+'">'+subtotal+'</span></td>'+
            '<td><button class="btn btn-info" type="button" onclick="ModificarSubtotales()" title="Modificar Subtotales"><i class="fa fa-refresh"></i></button></td>'+
            '</tr>'
            contador++
            detalle++
            $('#TablaDetalles').append(fila)
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
            $("#BtnAuxiliar").show()
        }
        else
        {
            $("#BtnAuxiliar").hide()
            contador=0
        }
    }
    IniciarIngresos()