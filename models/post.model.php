<?php

require_once "connection.php";

class PostModel{


    static public function postData($table,$data){


        // Recogemos en dos variables primero el nombre de las columnas y segundo el valor que se le quiere dar a

        $columns = "";
        $valors= "";

        foreach($data as $key => $value){

            $columns.=$key.",";

            $valors .= ":".$key.",";

        }

        //Eliminamos el último carácter para eliminar la última coma que hemos concatenado.
        
        $columns = substr($columns, 0, -1);
        $valors = substr($valors, 0, -1);

      // Utilizamos una sentencia dinámica para poder guardar valores en cualquier tabla.  

      $sql = "INSERT INTO $table ($columns) VALUES ($valors)";

      // Guardamos la conexión en una variable para que después nos pueda devolver el número de id que ha creado si se produce satisfactoriamente.

      $link = Connection::connect();

      //Preparamos la sentencia

      $stmt = $link -> prepare($sql);
      
      // itera sobre un array asociativo de datos, enlazando cada valor del array a un parámetro de consulta en una consulta SQL de forma segura para evcitar inyección SQL

      foreach($data as $key => $value){

        $stmt->bindParam(":".$key, $data[$key], PDO::PARAM_STR);

      }

      //Ejecutamos la sentencia

      if($stmt -> execute()){
        
        $response = array(
            "Ultimo id insertado" => $link->lastInsertId(),
            "comment" => "El proceso se ha ejecutado correctamente"
        );

        return $response;
      }else{

        return $link->errorInfo();

      }
    
    
    }
}