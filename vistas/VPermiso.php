<?php
ob_start();
session_start();

if(!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
  require "VHeader.php" 
  if($_SESSION['acceso']==1)
  { 
?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h1 class="box-title">
              Listado de Permisos del Sistema
              <button class="btn btn-success" onclick="MostrarFormulario(true)" id="BtnAgregar">
                <i class="fa fa-plus-circle"></i> Agregar
              </button>
            </h1>
            <div class="box-tools pull-right"></div>
          </div>
          <!-- /.box-header -->
          <!-- centro -->
          <div class="panel-body table-responsive" id="TablaPermisos">
            <table id="TablaListadoPermisos" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Nombre</th>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <th>Nombre</th>
              </tfoot>
            </table>
          </div>
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
require "VFooter.php";
?>

<script src="../vistas/scripts/permiso.js"></script>
<?php
}
ob_end_flush();
?>