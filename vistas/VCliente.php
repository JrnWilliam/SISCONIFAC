<?php
ob_start();
session_start();
if(!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
  require 'VHeader.php';
  if($_SESSION['ventas']==1)
  {
?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h1 class="box-title">  
            Gestión de Clientes
              <button class="btn btn-success" onclick="MostrarFormularioClientes(true)" id="BtnAgregar">
                <i class="fa fa-plus-circle"></i> Agregar
              </button>
            </h1>
            <div class="box-tools pull-right"></div>
          </div>
          <!-- /.box-header -->
          <!-- centro -->
          <div class="panel-body table-responsive" id="TablaClientes">
            <table id="TablaListadoClientes" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Tipo de Documento</th>
                <th>Número de Documento</th>
                <th>Telefono</th>
                <th>E-mail</th>
              </thead>
              <tbody>

              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Tipo de Documento</th>
                <th>Número de Documento</th>
                <th>Telefono</th>
                <th>E-mail</th>
              </tfoot>
            </table>
          </div>
        <div class="panel-body" id="FormularioClientes">
        <form name="FormularioRegistroClientes" id="FormularioRegistroClientes" method="POST" >
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Nombre del Cliente: </label>
            <input type="hidden" name="idpersona" id="idpersona">
            <input type="hidden" name="tipopersona" id="tipopersona" value="Cliente">
            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" autocomplete="off" required>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Tipo de Documento: </label>
            <select name="tipodocumento" id="tipodocumento" class="form-control selectpicker" data-live-search="true" required>
              <option value="Pasaporte">Pasaporte</option>
              <option value="Cédula">Cédula</option>
            </select>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Número de Documento: </label>
            <input type="text" class="form-control" name="numdocumento" id="numdocumento" placeholder="Documento" maxlength="20" required>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Dirección: </label>
            <input type="text" class="form-control" name="direccion" id="direccion" maxlength="70" autocomplete="off" placeholder="Dirección">
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Teléfono: </label>
            <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" maxlength="20" autocomplete="off">
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Email: </label>
            <input type="text" class="form-control" name="email" id="email" autocomplete="off" placeholder="Email" maxlength="50" required>
          </div>
          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button class="btn btn-primary" type="submit" id="BtnGuardar" title="Guardar Registros">
              <i class="fa fa-save"></i> <strong>Guardar</strong>
            </button>
            <button class="btn btn-danger" onclick="CerrarFormularioClientes()" type="button" title="Cerrar Formulario">
            <i class="fa fa-arrow-circle-left">
            </i> <strong>Cancelar</strong>
          </button>
          </div>
          </div>
        </form>
        </div>
          <!--Fin centro -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<?php
  }
  else
  {
    require 'noacceso.php';
  }
require 'VFooter.php'
?>
<script src="../vistas/scripts/cliente.js"></script>
<?php
}
ob_end_flush();
?>