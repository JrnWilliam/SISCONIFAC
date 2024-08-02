<?php
  function BackUpDB($host,$user,$pass,$name,$tables="*")
  {
    $return = "";
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
    $fecha = date("dmY-HisA");

    $operador = fopen('../files/BackUps/'. $name . $fecha . '.sql','w+');
    fwrite($operador,$return);
    fclose($operador);
  }  
?>