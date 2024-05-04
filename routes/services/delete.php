<?php

require_once "models/connection.php";
require_once "controllers/delete.controller.php";

if(isset($_GET["id"]) && isset($_GET["nameId"])){
    
   

    $columns = array($_GET["nameId"]);

    /*==========================================
    Validar la tabla y columnas
    ==========================================*/

    if(empty(Connection::getColumnsData($table, $columns))){
        $json = array(
            'status' => 400,
            'results' => "Error, las columnas no coinciden con la base de datos"
        );

        echo json_encode($json, http_response_code($json["status"]));

        return;
    }

     //PETICIÓN DELETE PARA USUARIOS AUTORIZADOS

     if(isset($_GET["token"])){

        $tableToken = $_GET["table"] ?? "employees";
        $suffix = $_GET["suffix"] ?? "employee";

        $validate = Connection::tokenValidate($_GET["token"], $tableToken, $suffix);

        /*==========================================
        Solicitamos respuesta del controlador para eliminar datos en cualquier tabla
        ==========================================*/
        if($validate == "ok"){
            $response = new DeleteController();
            $response -> deleteData($table,$_GET['id'],$_GET['nameId']);
        }

        if($validate == "expirado"){

            $json = array(
                'status' => 303,
                'results' => "Error, el token ha expirado"
            );
            echo json_encode($json, http_response_code($json["status"]));    
            return;
        }
        
        if($validate == "no-autorizado"){

            $json = array(
                'status' => 400,
                'results' => "El usuario no está autorizado"
            ); 
            echo json_encode($json, http_response_code($json["status"]));
            return;
        }  



    }else{

        //Cuando no envía token

        if($validate == "expirado"){
            $json = array(
                'status' => 400,
                'results' => "Error: Se requiere autorización"
            );    
            echo json_encode($json, http_response_code($json["status"]));
            return;
        }
    }
}