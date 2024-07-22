<?php
ob_start();
session_start();

if(!isset($_SESSION["nombre"]))
{
    header("Location: login.html");
}
else
{
    require "VHeader.php";
    if($_SESSION['ventas'] == 1)
    {
?>
    <div class="content-wrapper">
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">
                  Gestion de Ventas
                  <button class="btn btn-success" onclick="MostrarFormularioVenta(true)" id="BtnAgregar">
                    <i class="fa fa-plus-circle"></i> Agregar
                  </button>
                </h1>
                <div class="box-tools pull-right"></div>
              </div>
              <div class="panel-body table-responsive" id="TablaVenta">
                <table id="TablaListadoVenta" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Número</th>
                    <th>Total Ventas</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Número</th>
                    <th>Total Ventas</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" id="FormularioVenta">
                <form name="FormularioRegistroVenta" id="FormularioRegistroVenta" method="POST">
                  <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <label for="idcliente">Cliente: </label>
                    <input type="hidden" name="idventa" id="idventa">
                    <div role="listbox">
                    <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" required></select>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label for="fechahora">Fecha: </label>
                    <input type="date" name="fechahora" id="fechahora" class="form-control" placeholder="Selecciona la Fecha y Hora" title="Selecciona la Fecha y Hora" required>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="tipocomprobante">Tipo de Comprobante: </label>
                    <div role="listbox">
                    <select name="tipocomprobante" id="tipocomprobante" class="form-control selectpicker" required>
                      <option value="Boleta">Boleta</option>
                      <option value="Factura">Factura</option>
                      <option value="Ticket">Ticket</option>
                    </select>
                    </div>
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <label for="seriecomprobante">Serie: </label>
                    <input type="text" class="form-control" name="seriecomprobante" id="seriecomprobante" maxlength="7" autocomplete="off" placeholder="Número de Serie">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <label for="numcomprobante">Número: </label>
                    <input type="text" name="numcomprobante" id="numcomprobante" class="form-control" maxlength="10" placeholder="Número de Comprobante" autocomplete="off" required>
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <label for="impuesto">Impuesto: </label>
                    <input type="text" class="form-control" name="impuesto" id="impuesto" placeholder="Impuesto" required>
                    <br>
                  </div>
                  <div class="form-group col-lg-3 col-md-2 col-sm-6 col-xs-12">
                    <a data-toggle="modal" href="#VentanaModal">
                      <button id="AgregarArticulo" type="button" class="btn btn-primary"><span class="fa fa-plus">Agregar Articulos</span></button>
                    </a>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table id="TablaDetallesVenta" class="table table-striped table-bordered table-condensed table-hover">
                      <thead style="background-color:#B4F8C8">
                        <th>Opciones</th>
                        <th>Artículo</th>
                        <th>Cantidad</th>
                        <th>Precio Venta</th>
                        <th>Descuento</th>
                        <th>Sub Total</th>
                      </thead>
                      <tfoot>
                        <th>TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><h4 id="total">C$ 0.00</h4><input type="hidden" name="totalcompra" id="totalcompra"></th>
                      </tfoot>
                      <tbody>

                      </tbody>
                    </table>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" type="submit" id="BtnGuardar" title="Guardar Registros">
                      <i class="fa fa-save"> Guardar</i>
                    </button>
                    <button id="BtnCancelar" class="btn btn-danger" onclick="CerrarFormularioVenta()" type="button" title="Cerrar Formulario">
                      <i class="fa fa-arrow-circle-left">
                        Cancelar
                      </i>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
<?php
    }
    else
    {
        require "noacceso.php";
    }
    require "VFooter.php";
?>
<script src="../vistas/scripts/venta.js"></script>
<?php
}
ob_end_flush(); 
?>