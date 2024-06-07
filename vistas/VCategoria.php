<?php
require 'header.php'
?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h1 class="box-title">
              Gestion de Categorias
              <button class="btn btn-success" onclick="MostrarFormulario(true)" id="BtnAgregar">
                <i class="fa fa-plus-circle"></i> Agregar
              </button>
            </h1>
            <div class="box-tools pull-right"></div>
          </div>
          <!-- /.box-header -->
          <!-- centro -->
          <div class="panel-body table-responsive" id="listado_registros">
            <table id="TablaListado" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
              </thead>
              <tbody>

              </tbody>
              <tfoot>
              <th>Opciones</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
              </tfoot>
            </table>
          </div>
        <div class="panel-body" style="height: 400px" id="formulario_registros">
        <form name="Formulario" id="Formulario" method="POST">
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Nombre: </label>
            <input type="hidden" name="idcategoria" id="idcategoria">
            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50" placeholder="Nombre" required>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Descripción: </label>
            <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
          </div>
          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button class="btn btn-primary" type="submit" id="BtnGuardar">
              <i class="fa fa-save"> Guardar</i>
            </button>
            <button class="btn btn-danger" onclick="CerrarFormulario()" type="button">
            <i class="fa fa-arrow-circle-left">
              Cancelar
            </i>
          </button>
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
require 'footer.php'
?>

<script src="scripts/categoria.js"></script>