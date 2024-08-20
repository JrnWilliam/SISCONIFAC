<?php
  function BackUpDB($host,$user,$pass,$name,$tables="*")
  {
    $return = "";
    $return .= "CREATE DATABASE IF NOT EXISTS `$name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;\n";
    $return .= "USE `$name`;\n\n";
    $conexion = new mysqli($host,$user,$pass,$name);

    if($tables =='*')
    {
        $tables = array();
        $resultado = $conexion->query('SHOW TABLES');
        while($fila = mysqli_fetch_row($resultado))
        {
            $tables[] = $fila[0];
        }
    }
    else
    {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    foreach($tables as $tabla)
    {
        $result = $conexion->query("SELECT * FROM $tabla");
        $numcampos = $result->field_count;

        $return .= 'DROP TABLE IF EXISTS '.$tabla.';';
        $fila2 = mysqli_fetch_row($conexion->query('SHOW CREATE TABLE '.$tabla));
        $return.= "\n\n".$fila2[1].";\n\n";
        
        for($i = 0; $i < $numcampos; $i++)
        {
            while($fila = mysqli_fetch_row($result))
            {
                $return.= 'INSERT INTO '.$tabla.' VALUES(';
                for($j = 0; $j < $numcampos; $j++)
                {
                    $fila[$j] = addslashes($fila[$j]);
                    $fila[$j] = preg_replace("/\n/","\\n",$fila[$j]);
                    if(isset($fila[$j]))
                    {
                        $return.='"'.$fila[$j].'"';
                    }
                    else
                    {
                        $return.='""';
                    }
                    if($j<($numcampos-1))
                    {
                        $return.= ',';
                    }
                }
                $return.= "); \n";
            }
        }
        $return.= "\n\n\n";
    }

    // Respaldar los triggers
    $triggers_result = $conexion->query("SHOW TRIGGERS");
    while ($trigger = mysqli_fetch_assoc($triggers_result))
    {
        $trigger_name = $trigger['Trigger'];
        $trigger_table = $trigger['Table'];
        $trigger_timing = $trigger['Timing'];
        $trigger_event = $trigger['Event'];
        $trigger_statement = $trigger['Statement'];

        $return .= "DELIMITER //\n";
        $return .= "CREATE TRIGGER `$trigger_name` $trigger_timing $trigger_event ON `$trigger_table` FOR EACH ROW $trigger_statement //\n";
        $return .= "DELIMITER ;\n\n";
    }

    $fecha = date("dmY-HisA");

    $operador = fopen('../files/BackUps/'. $name . $fecha . '.sql','w+');
    fwrite($operador,$return);
    fclose($operador);
  }  
?>