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

      $compras10 = $ObjConsulta->ComprasUltimos10Dias();
      $fechascompras = '';
      $totalescompras = '';

      while($registrofcompras = $compras10->fetch_object())
      {
        $fechascompras = $fechascompras.'"'.$registrofcompras->fecha.'",';
        $totalescompras = $totalescompras.$registrofcompras->total.',';
      }

      $fechascompras = substr($fechascompras,0,-1);
      $totalescompras = substr($totalescompras,0,-1);

      $ventas10 = $ObjConsulta->VentasUltimos10Dias();
      $fechasventas = '';
      $ventastotales = '';

      while($registrofventas = $ventas10->fetch_object())
      {
        $fechasventas = $fechasventas.'"'.$registrofventas->fechav.'",';
        $ventastotales = $ventastotales.$registrofventas->totalv.',';
      }

      $fechasventas = substr($fechasventas,0,-1);
      $ventastotales = substr($ventastotales,0,-1);
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
        <div class="panel-body">
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                Compras de los Ultimos 10 Días
              </div>
              <div class="box-body">
                <canvas id="compras" width="400" height="300">

                </canvas>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                Ventas de los Ultimos 10 Días
              </div>
              <div class="box-body">
                <canvas id="ventas" width="400" height="300">

                </canvas>
              </div>
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
    require 'VFooter.php'
?>
<script src="../public/js/chart.min.js"></script>
<script src="../public/js/Chart.bundle.min.js"></script>
<script>
  var ctx = document.getElementById("compras").getContext('2d');
  var compra = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechascompras;?>],
        datasets: [{
            label: 'N° Compras en C$ de los Ultimos 10 Días',
            data: [<?php echo $totalescompras;?>],
            backgroundColor: [
                '#FFAEBC',
                '#B4F8C8',
                '#A0E7E5',
                '#FBE7C6',
                '#FFCE66',
                '#F9AB40',
                '#A9E5D5',
                '#44455B',
                '#F0F7E0',
                '#D3BBDD'
            ],
            borderColor: [
                '#FFAEBC',
                '#B4F8C8',
                '#A0E7E5',
                '#FBE7C6',
                '#FFCE66',
                '#F9AB40',
                '#A9E5D5',
                '#44455B',
                '#F0F7E0',
                '#D3BBDD'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
<script>
  var ctx = document.getElementById("ventas").getContext('2d');
  var venta = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechasventas;?>],
        datasets: [{
            label: 'N° Ventas en C$ de los Ultimos 10 Días',
            data: [<?php echo $ventastotales;?>],
            backgroundColor: [
                '#FFAEBC',
                '#B4F8C8',
                '#A0E7E5',
                '#FBE7C6',
                '#FFCE66',
                '#F9AB40',
                '#A9E5D5',
                '#44455B',
                '#F0F7E0',
                '#D3BBDD'
            ],
            borderColor: [
                '#FFAEBC',
                '#B4F8C8',
                '#A0E7E5',
                '#FBE7C6',
                '#FFCE66',
                '#F9AB40',
                '#A9E5D5',
                '#44455B',
                '#F0F7E0',
                '#D3BBDD'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
<?php
}
ob_end_flush()  
?>