<?php

require_once "models/connection.php";
require_once "controllers/post.controller.php";

if(isset($_POST)){

    $columns = array();

    //Hacemos un foreach para traernos los nombres de las columnas

    foreach(array_keys($_POST) as $key => $value){
        array_push($columns,$value);
    }

    // Validamos la tabla y las columnas.
    // Si lo que nos devuelve está vacío significa que hay algún error en algún nombre de tabla o columna.

    if(empty(Connection::getColumnsData($table, $columns))){
        $json = array(
            'status' => 400,
            'results' => "Error, las columnas no coinciden con la base de datos"
        );

        echo json_encode($json, http_response_code($json["status"]));

        return;
    }



    //PETICIÓN POST PARA EL REGISTRO DE USUARIOS

    $response = new PostController();

    if(isset($_GET["register"])&&$_GET["register"]==true){

        $suffix= $_GET["suffix"] ?? "user";

        $response -> postRegister($table,$_POST,$suffix);

    //PETICIÓN POST PARA EL LOGIN DEL USUARIO


    }else if(isset($_GET["login"])&&$_GET["login"]==true){

        $suffix= $_GET["suffix"] ?? "user";

        $response -> postLogin($table,$_POST,$suffix);

    }else{

        //SOLICITAMOS RESPUESTA DEL CONTROLADOR PARA CREAR DATOS

        $response -> postData($table,$_POST);
    }


}