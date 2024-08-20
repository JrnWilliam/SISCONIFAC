<?php
  if(strlen(session_id())<1)
  {
    session_start();
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SISCONIFAC</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../public/css/bootstrap.min.css">
  <link rel="stylesheet" href="../public/css/font-awesome.css">
  <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../public/css/_all-skins.min.css">
  <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
  <link rel="shortcut icon" href="../public/img/favicon.ico">
  <link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
  <link rel="stylesheet" href="../public/datatables/buttons.dataTables.min.css">
  <link rel="stylesheet" href="../public/datatables/responsive.dataTables.min.css">
  <link rel="stylesheet" href="../public/css/bootstrap-select.min.css">
</head>

<body class="hold-transition skin-black sidebar-mini">
  <div class="wrapper">

    <header class="main-header">


      <a href="#" class="logo">

        <span class="logo-mini"><img src="../public/img/ferreteria.png" alt="Ferreteria Logo"></span>

        <span class="logo-lg"><img src="../public/img/ferreteria.png" alt="Ferreteria Logo"><b>SISCONIFAC</b></span>
      </a>


      <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Navegación</span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="../files/usuarios/<?php echo $_SESSION['imagen'];?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo $_SESSION['nombre'];?></span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-header">
                  <img src="../files/usuarios/<?php echo $_SESSION['imagen'];?>" class="img-circle" alt="User Image">
                  <p>
                    <?php
                      echo $_SESSION['cargo']; 
                    ?>
                    <small></small>
                  </p>
                </li>
                <li class="user-footer">

                  <div class="pull-right">
                    <a href="../ajax/Usuario.php?operacion=CerrarSesion" class="btn btn-default btn-flat">Cerrar</a>
                  </div>
                </li>
              </ul>
            </li>

          </ul>
        </div>

      </nav>
    </header>
    <aside class="main-sidebar">
      <section class="sidebar">
        <ul class="sidebar-menu">
          <!-- <li class="header"></li> -->
          <?php
          if ($_SESSION['escritorio'] == 1)
          {
            echo '<li>
              <a href="VEscritorio.php">
                <i class="fa fa-tasks"></i> <span>Escritorio</span>
              </a>
            </li>';
          }
          ?>
          <?php
            if($_SESSION['almacen']==1)
            {
              echo '<li class="treeview">
            <a href="#">
              <i class="fa fa-laptop"></i>
              <span>Almacén</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="VArticulo.php"><i class="fa fa-circle-o"></i> Artículos</a></li>
              <li><a href="VCategoria.php"><i class="fa fa-circle-o"></i> Categorías</a></li>
            </ul>
          </li>';
            }
          ?>
          <?php
            if($_SESSION['compras']==1)
            {
              echo '<li class="treeview">
            <a href="#">
              <i class="fa fa-th"></i>
              <span>Compras</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="VIngreso.php"><i class="fa fa-circle-o"></i> Ingresos</a></li>
              <li><a href="VProveedor.php"><i class="fa fa-circle-o"></i> Proveedores</a></li>
            </ul>
          </li>';
            }
          ?>
          <?php
            if($_SESSION['ventas']==1)
            {
              echo '<li class="treeview">
            <a href="#">
              <i class="fa fa-shopping-cart"></i>
              <span>Ventas</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="VVenta.php"><i class="fa fa-circle-o"></i> Ventas</a></li>
              <li><a href="VCliente.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
            </ul>
          </li>';
            }
          ?>
          <?php
            if($_SESSION['acceso']==1)
            {
              echo '<li class="treeview">
            <a href="#">
              <i class="fa fa-folder"></i> <span>Acceso</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="VUsuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
              <li><a href="VPermiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>

            </ul>
          </li>';
            }
          ?>
          <?php
            if($_SESSION['consultacompras']==1)
            {
              echo '<li class="treeview">
            <a href="#">
              <i class="fa fa-bar-chart"></i> <span>Consulta Compras</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="VConsultaCompras.php"><i class="fa fa-circle-o"></i> Consulta Compras</a></li>
            </ul>
          </li>';
            }
          ?>
          <?php
            if($_SESSION['consultaventas']==1)
            {
              echo '<li class="treeview">
            <a href="#">
              <i class="fa fa-bar-chart"></i> <span>Consulta Ventas</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="VConsultaVentas.php"><i class="fa fa-circle-o"></i> Consulta Ventas</a></li>
            </ul>
          </li>';
            }
          ?>
          <li class="treeview">
            <a href="#">
            <i class="fa fa-gears"></i>
            <span>Herramientas</span>
            <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
            <li>
                <a href="#" onclick="IniciarBackUp(event)">
                <i class="fa fa-plus-square"></i> <span>Back Up</span>
                <small class="label pull-right bg-red">🔒</small>
                </a>
            </li>
            <li class="treeview">
              <a href="VRestaurar.php">
              <i class="fa fa-files-o"></i>
              <span>Restaurar BD</span>
              <small class="label pull-right bg-green">📥</small>
              </a>
            </li>
            </ul>
          </li>
          <!-- <?php
            if($_SESSION['acceso']==1)
            {
              echo '<li>
                      <a href="#" onclick="IniciarBackUp(event)">
                      <i class="fa fa-plus-square"></i> <span>Back Up</span>
                      <small class="label pull-right bg-red">🔒</small>
                      </a>
                    </li>';
            }
          ?> -->
          <!-- <li>
            <a href="#">
              <i class="fa fa-info-circle"></i> <span>Acerca De...</span>
              <small class="label pull-right bg-yellow">IT</small>
            </a>
          </li> -->

        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>

    <script src="../vistas/scripts/backup.js"></script>