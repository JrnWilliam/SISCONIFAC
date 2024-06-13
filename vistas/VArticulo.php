<?php
require 'VHeader.php'
?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h1 class="box-title">  
            Gestion de Articulos
              <button class="btn btn-success" onclick="MostrarFormulario(true)" id="BtnAgregar">
                <i class="fa fa-plus-circle"></i> Agregar
              </button>
            </h1>
            <div class="box-tools pull-right"></div>
          </div>
          <!-- /.box-header -->
          <!-- centro -->
          <div class="panel-body table-responsive" id="listado_registros">
            <table id="TablaListadoArticulos" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Codigo</th>
                <th>Stock</th>
                <th>Descripción</th>
                <th>Imagen</th>
                <th>Estado</th>
              </thead>
              <tbody>

              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Codigo</th>
                <th>Stock</th>
                <th>Descripción</th>
                <th>Imagen</th>
                <th>Estado</th>
              </tfoot>
            </table>
          </div>
        <div class="panel-body" style="height: 400px" id="formulario_registros">
        <form name="Formulario" id="Formulario" method="POST" >
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Nombre: </label>
            <input type="hidden" name="idarticulo" id="idarticulo">
            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" autocomplete="off" required>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Categoria: </label>
            <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-live-search="true" required></select>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Stock: </label>
            <input type="number" class="form-control" name="stock" id="stock" placeholder="Stock" required>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Descripción: </label>
            <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" autocomplete="off" placeholder="Descripción">
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Imagen: </label>
            <input type="file" class="form-control" name="imagen" id="imagen">
            <input type="hidden" name="imagen_actual" id="imagen_actual">
            <img src="" id="img_actual" alt="Imagen actual" style="max-width: 100px; max-height: 100px;">
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Código: </label>
            <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Codigo de Barras" autocomplete="off" required>
          </div>
          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button class="btn btn-primary" type="submit" id="BtnGuardar" title="Guardar Registros">
              <i class="fa fa-save"> Guardar</i>
            </button>
            <button class="btn btn-danger" onclick="CerrarFormulario()" type="button" title="Cerrar Formulario">
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
require 'VFooter.php'
?>

<script src="scripts/articulo.js"></script>
<script src="../public/js/JsBarcode.all.min.js"></script>