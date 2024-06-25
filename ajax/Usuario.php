<?php
require_once "../modelos/CUsuario.php";

$usuario = new Cusuario();

$idusuario = isset($_POST["idusuario"])?LimpiarCadena($_POST["idusuario"]):"";
$nombre = isset($_POST["nombre"])?LimpiarCadena($_POST["nombre"]):"";
$tipodocumento = isset($_POST["tipodocumento"])?LimpiarCadena($_POST["tipodocumento"]):"";
$numdocumento = isset($_POST["numdocumento"])?LimpiarCadena($_POST["numdocumento"]):"";
$direccion = isset($_POST["direccion"])?LimpiarCadena($_POST["direccion"]):"";
$telefono = isset($_POST["telefono"])?LimpiarCadena($_POST["telefono"]):"";
$email = isset($_POST["email"])?LimpiarCadena($_POST["email"]):"";
$cargo = isset($_POST["cargo"])?LimpiarCadena($_POST["cargo"]):"";
$login = isset($_POST["login"])?LimpiarCadena($_POST["login"]):"";
$clave = isset($_POST["clave"])?LimpiarCadena($_POST["clave"]):"";
$imagen = isset($_POST["imagen"])?LimpiarCadena($_POST["imagen"]):"";
$condicion = isset($_POST["condicion"])?LimpiarCadena($_POST["condicion"]):"";


?>