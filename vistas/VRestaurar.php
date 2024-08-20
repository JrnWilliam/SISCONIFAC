<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
    header("Location: login.html");
}
else
{
    require 'VHeader.php';
    if ($_SESSION['acceso'] == 1)
    {
?>
        <div class="content-wrapper">
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h1 class="box-title">
                                    Gestion de Categorias
                                </h1>
                            </div>
                            <div class="panel-body">
                                <div class="form-goup col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                    <label for="script">Seleccione el Script Para Restaurar la Base de Datos</label>
                                    <input type="file" class="form-control" name="script" id="script">
                                </div>
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <br>
                                <button class="btn btn-primary" type="submit" id="BtnGuardar" title="Guardar Registros">
                                    <i class="fa fa-save"></i>
                                    <strong>Restaurar</strong>
                                </button>
                                <button class="btn btn-danger" onclick="CerrarFormulario()" type="button" title="Cerrar Formulario">
                                    <i class="fa fa-arrow-circle-left"></i>
                                    <strong>Cancelar</strong>
                                </button>
                                </div>
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
        require 'noacceso.php';
    }
    require 'VFooter.php';
    ?>

<?php
}
ob_end_flush();
?>