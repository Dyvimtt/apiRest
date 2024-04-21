<?php

require_once "connection.php";
require_once "get.model.php";

class DeleteModel{

        /*==========================================
        Petición DELETE para eliminar datos de forma dinámica
        ==========================================*/

    static public function deleteData($table,$id,$nameId){

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
        Eliminamos registros.
        ==========================================*/


        $sql = "DELETE FROM $table WHERE $nameId = :$nameId";


        $link = Connection::connect();
        $stmt = $link -> prepare($sql);

          
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