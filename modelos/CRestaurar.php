<?php
  function RestaurarDB($host, $usuario, $pass, $ruta)
  {
    $conexion = new mysqli($host, $usuario, $pass);

    if ($conexion->connect_error)
    {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Leer el contenido del archivo de respaldo SQL
    $sql = file_get_contents($ruta);
    if ($sql === false)
    {
        die("Error al leer el archivo de respaldo: $ruta");
    }

    // Desactivar temporalmente las verificaciones de claves foráneas
    $conexion->query('SET foreign_key_checks = 0');

    // Procesar las consultas en el archivo SQL
    $queries = explode(";", $sql);
    $delimiterFound = false;
    $statement = '';

    foreach ($queries as $query)
    {
        // Detectar el inicio de un bloque de código delimitado
        if (stripos($query, 'DELIMITER //') !== false)
        {
            $statement .= $query . ';';
            $delimiterFound = true;
            continue;
        }
        // Detectar el final del bloque de código delimitado
        elseif ($delimiterFound && stripos($query, 'END //') !== false)
        {
            $statement .= $query . ';';  // Completar la sentencia con el bloque completo
            $statement = trim(preg_replace('/DELIMITER\s+\S+|\/\/|^\s*--.*$/m', '', $statement));
            $statement = $statement . ';';
            if ($conexion->query($statement) === false)
            {
                echo "Error al ejecutar la consulta: " . $conexion->error . "\n";
                echo "Consulta: $statement\n";
                return false;
            }
            $delimiterFound = false;
            $statement = '';
            continue;
        }
        // Acumular las partes de la sentencia delimitada
        if ($delimiterFound)
        {
            $statement .= $query . ';';
        }
        else
        {
            // Ejecutar sentencias normales (no delimitadas)
            if (trim($query))
            {
                if ($conexion->query($query . ';') === false)
                {
                    echo "Error al ejecutar la consulta: " . $conexion->error . "\n";
                    echo "Consulta: $query\n";
                    return false;
                }
            }
        }
    }

    // Reactivar las verificaciones de claves foráneas
    $conexion->query('SET foreign_key_checks = 1');
    $conexion->close();
    return true;
}
?>