<?php
  function BackUpDB($host, $user, $pass, $name, $tables = "*")
  {
      $return = "";
      $return .= "CREATE DATABASE IF NOT EXISTS `$name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;\n";
      $return .= "USE `$name`;\n\n";
      $conexion = new mysqli($host, $user, $pass, $name);
  
      if ($tables == '*') {
          $tables = array();
          $resultado = $conexion->query('SHOW TABLES');
          while ($fila = mysqli_fetch_row($resultado)) {
              $tables[] = $fila[0];
          }
      } else {
          $tables = is_array($tables) ? $tables : explode(',', $tables);
      }
  
      foreach ($tables as $tabla) {
          $result = $conexion->query("SELECT * FROM $tabla");
          $numcampos = $result->field_count;
  
          $return .= 'DROP TABLE IF EXISTS ' . $tabla . ';';
          $fila2 = mysqli_fetch_row($conexion->query('SHOW CREATE TABLE ' . $tabla));
  
          // Procesar el CREATE TABLE para eliminar las llaves foráneas y corregir la coma extra
          $createTableQuery = $fila2[1];
          // Eliminar cualquier línea que contenga FOREIGN KEY o REFERENCES
          $lines = explode("\n", $createTableQuery);
          $filteredLines = array_filter($lines, function ($line) {
              return !preg_match('/FOREIGN KEY|REFERENCES/', $line);
          });
  
          // Unir las líneas y eliminar la coma final antes de ') ENGINE=' si existe
          $createTableQuery = implode("\n", $filteredLines);
          $createTableQuery = preg_replace('/,\s*\)/', "\n)", $createTableQuery);
          
          $return .= "\n\n" . $createTableQuery . ";\n\n";
  
          for ($i = 0; $i < $numcampos; $i++) {
              while ($fila = mysqli_fetch_row($result)) {
                  $return .= 'INSERT INTO ' . $tabla . ' VALUES(';
                  for ($j = 0; $j < $numcampos; $j++) {
                      $fila[$j] = addslashes($fila[$j]);
                      $fila[$j] = preg_replace("/\n/", "\\n", $fila[$j]);
                      if (isset($fila[$j])) {
                          $return .= '"' . $fila[$j] . '"';
                      } else {
                          $return .= '""';
                      }
                      if ($j < ($numcampos - 1)) {
                          $return .= ',';
                      }
                  }
                  $return .= ");\n";
              }
          }
          $return .= "\n\n\n";
      }
  
      // Respaldar los triggers
      $triggers_result = $conexion->query("SHOW TRIGGERS");
      while ($trigger = mysqli_fetch_assoc($triggers_result)) {
          $trigger_name = $trigger['Trigger'];
          $trigger_table = $trigger['Table'];
          $trigger_timing = $trigger['Timing'];
          $trigger_event = $trigger['Event'];
          $trigger_statement = $trigger['Statement'];
  
          $return .= "DELIMITER //\n";
          $return .= "CREATE TRIGGER `$trigger_name` $trigger_timing $trigger_event ON `$trigger_table` FOR EACH ROW $trigger_statement //\n";
          $return .= "DELIMITER ;\n\n";
      }
  
      // Añadir las llaves foráneas después de crear las tablas
      foreach ($tables as $tabla) {
          $foreign_keys = $conexion->query("SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
                                            FROM information_schema.KEY_COLUMN_USAGE 
                                            WHERE TABLE_SCHEMA = '$name' AND TABLE_NAME = '$tabla' AND REFERENCED_TABLE_NAME IS NOT NULL");
          while ($fk = mysqli_fetch_assoc($foreign_keys)) {
              $return .= "ALTER TABLE `$tabla` ADD CONSTRAINT `{$fk['CONSTRAINT_NAME']}` FOREIGN KEY (`{$fk['COLUMN_NAME']}`) REFERENCES `{$fk['REFERENCED_TABLE_NAME']}`(`{$fk['REFERENCED_COLUMN_NAME']}`) ON DELETE NO ACTION ON UPDATE NO ACTION;\n";
          }
          $return .= "\n";
      }
  
      $fecha = date("dmY-HisA");
      $operador = fopen('../files/BackUps/' . $name . $fecha . '.sql', 'w+');
      fwrite($operador, $return);
      fclose($operador);
  }
?>