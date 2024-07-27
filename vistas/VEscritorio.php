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
    if($_SESSION['escritorio']==1)
    {
      require '../modelos/CConsultas.php';
      $ObjConsulta = new CConsultas();

      $respuestac = $ObjConsulta->TotalComprasHoy();
      $registroc = $respuestac->fetch_object();
      $totalcompra = $registroc->totalcompra;

      $respuestav = $ObjConsulta->TotalVentasHoy();
      $registrov = $respuestav->fetch_object();
      $totalventa = $registrov->totalventa;
?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h1 class="box-title">
              Escritorio
            </h1>
            <div class="box-tools pull-right"></div>
          </div>
          <div class="panel-body">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h4 style="font-size: 17px">
                            <strong>C$ <?php echo $totalcompra?></strong>
                            <p>Compras</p>
                        </h4>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag">
                        </i>
                    </div>
                    <a href="VIngreso.php" class="small-box-footer">Compras <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h4 style="font-size: 17px">
                            <strong>C$ <?php echo $totalventa?></strong>
                            <p>Ventas</p>
                        </h4>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag">
                        </i>
                    </div>
                    <a href="VVenta.php" class="small-box-footer">Ventas <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
          </div>
        <div class="panel-body" style="height: 400px">
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
    require 'VFooter.php'
?>
<script src="../public/js/chart.min.js"></script>
<script src="../public/js/Chart.bundle.min.js"></script>
<?php
}
ob_end_flush()  
?>