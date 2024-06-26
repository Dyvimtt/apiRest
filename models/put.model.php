<?php

require_once "connection.php";
require_once "get.model.php";

class PutModel{

        /*==========================================
        Petición PUT para editar datos de forma dinámica
        ==========================================*/

    static public function putData($table,$data,$id,$nameId){

        /*==========================================
        Validar el Id para asegurarnos de que existe.
        ==========================================*/

        $response = GetModel::getDataFilter($table, $nameId, $nameId, $id, null, null, null, null);
        if(empty($response)){
            $response = array(
                "comment" => "El numero de id seleccionado no existe en la base de datos"
            );
    
            return $response;
        }

        /*==========================================
        Actualizamos registros.
        ==========================================*/

        $set= "";

        foreach($data as $key => $value){
            $set .= $key." = :".$key.",";
        }

        $set = substr($set, 0, -1);


        $sql = "UPDATE $table SET $set WHERE $nameId = :$nameId";


        $link = Connection::connect();
        $stmt = $link -> prepare($sql);

        //PREPARAMOS EL SET

        foreach($data as $key => $value){

            $stmt->bindParam(":".$key, $data[$key], PDO::PARAM_STR);
    
        }
          
        //Preparamos el WHERE

        $stmt->bindParam(":".$nameId, $id, PDO::PARAM_STR);

        if($stmt -> execute()){
        
            $response = array(
                "comment" => "El proceso se ha ejecutado correctamente"
            );
    
            return $response;
        }else{
    
            return $link->errorInfo();
    
        }
    }
}