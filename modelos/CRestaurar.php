<?php
  function RestaurarDB($host, $usuario, $pass, $DB, $ruta)
  {
    $conexion = new mysqli($host, $usuario, $pass, $DB);

    if ($conexion->connect_error)
    {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $sql = file_get_contents($ruta);
    if($sql === false)
    {
        die("Error al leer el archivo de respaldo: $ruta");
    }

    $queries = explode(";\n", $sql);

    foreach ($queries as $query)
    {
        $query = trim($query);
        if (!empty($query))
        {
            if ($conexion->query($query) === false)
            {
                echo "Error al ejecutar la consulta: " . $conexion->error . "\n";
                echo "Consulta: $query\n";
                return false;
            }
        }
    }
    $conexion->close();
    return true;
}
?>