<?php
  ob_start();
  session_start();
  if(!isset($_SESSION["nombre"]))
  {
    echo "Ingrese al Sistema Para Poder Generar Este Voucher";
  }
  else
  {
    if($_SESSION['compras']==1)
    {
?>

<?php
    }
    else
    {
        echo "No Tiene Permisos Para Generar Este Voucher";
    }
}
ob_end_flush();
?>