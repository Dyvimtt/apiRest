<?php

require_once "models/connection.php";
require_once "controllers/put.controller.php";

if(isset($_GET["id"]) && isset($_GET["nameId"])){
    
    /*==========================================
    Capturamos los datos del formulario, con parse_str dividimos en pares de clave-valor los datos que traemos por la solicitud http.
    ==========================================*/

    $data = array();
    parse_str(file_get_contents('php://input'),$data);

    /*==========================================
    Separar propiedades en un arreglo
    ==========================================*/

    $columns = array();
    
    //Hacemos un foreach para traernos los nombres de las columnas

    foreach(array_keys($data) as $key => $value){
        array_push($columns,$value);
    }

    // Método para evitar que salte error de SQL si insertamos una columna que no existe.
    
    array_push($columns, $_GET["nameId"]);

    $columns = array_unique($columns);

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

    //PETICIÓN PUT PARA USUARIOS AUTORIZADOS

    if(isset($_GET["token"])){

        $tableToken = $_GET["table"] ?? "employees";
        $suffix = $_GET["suffix"] ?? "employee";



        $validate = Connection::tokenValidate($_GET["token"], $tableToken, $suffix);

        if($validate == "ok"){

            /*==========================================
            Solicitamos respuesta del controlador para modificar los datos
            ==========================================*/
            $response = new PutController();
            $response -> putData($table,$data,$_GET['id'],$_GET['nameId']);

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