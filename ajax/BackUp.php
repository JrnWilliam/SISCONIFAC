<?php
require "../config/config.php";
include('../modelos/CBackUp.php'); 

BackUpDB(DBHOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
$fecha = $fecha = date("dmY-HisA");
$nombrearchivo = DB_NAME . $fecha . ".sql";

header("Content-disposition: attachment; filename=" . $nombrearchivo);
header('Content-Type: application/octet-stream');
readfile('../files/BackUps/'. $nombrearchivo);

echo $nombrearchivo;
?>