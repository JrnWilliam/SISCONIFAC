<?php
ob_start();
session_start();

if(!isset($_SESSION["nombre"]))
{
    header("Location: login.html");
}
else
{
    require "VHeader.php";
    if($_SESSION['ventas'] == 1)
    {
?>

<?php
    }
    else
    {
        require "noacceso.php";
    }
    require "VFooter.php";
?>
<script src="../vistas/scripts/venta.js"></script>
<?php
}
ob_end_flush(); 
?>