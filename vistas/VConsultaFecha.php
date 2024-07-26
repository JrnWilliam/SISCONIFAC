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
    if($_SESSION['consultacompras']==1)
    {
?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h1 class="box-title">
              Consultas de Compras Por Fecha
            </h1>
            <div class="box-tools pull-right"></div>
          </div>
          <div class="panel-body table-responsive" id="TablaConsulta">
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="Finicio">Fecha de Inicio</label>
              <input type="date" class="form-control" name="Finicio" id="Finicio" value="<?php echo date("Y-m-d"); ?>">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="Ffin">Fecha Final</label>
              <input type="date" class="form-control" name="Ffin" id="Ffin" value="<?php echo date("Y-m-d"); ?>">
            </div>
            <table id="TablaConsultaFecha" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Proveedor</th>
                <th>Comprobante</th>
                <th>Número</th>
                <th>Total Compra</th>
                <th>Impuesto</th>
                <th>Estado</th>
              </thead>
              <tbody>

              </tbody>
              <tfoot>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Proveedor</th>
                <th>Comprobante</th>
                <th>Número</th>
                <th>Total Compra</th>
                <th>Impuesto</th>
                <th>Estado</th>
              </tfoot>
            </table>
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
    require 'VFooter.php';
?>
<?php
}
ob_end_flush();  
?>