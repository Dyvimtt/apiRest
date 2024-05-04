<?php

    require_once "models/connection.php";

    // Coge la parte de la variable que pasamos por URL que va a continuación de la barra
    
    $routesArray = explode('/',$_SERVER['REQUEST_URI']);
    $routesArray = array_filter($routesArray);

    //CUANDO NO SE HACE NINGUNA PETICION A LA API

    if(empty($routesArray)){
        $json = array(
            'status' => 404,
            'results' => 'Not found'
        );    
        echo json_encode($json, http_response_code($json['status']));

    return;

    }

    // CUANDO SI SE HACEN PETICIONES A LA API

    if(count($routesArray) == 1 && isset($_SERVER['REQUEST_METHOD'])){

        $table = explode("?", $routesArray[1])[0];

        /*=================
        Validar llave secreta
        ==============*/

        if(!isset(getallheaders()["Authorization"]) || getallheaders()["Authorization"] != Connection::apikey()){

            $json = array(
                'status' => 400,
                'results' => 'No esta autorizado para utilizar esta aplicacion'
            );    
            echo json_encode($json, http_response_code($json['status']));
    
            return;

        }

        /*=================
        Peticiones GET
        ==============*/
        
        if($_SERVER['REQUEST_METHOD']== "GET"){

            include "services/get.php";

        }

        /*=================
        Peticiones POST
        ==============*/

        if($_SERVER['REQUEST_METHOD']== "POST"){
            
            include "services/post.php";
        }

        /*=================
        Peticiones PUT
        ==============*/

        if($_SERVER['REQUEST_METHOD']== "PUT"){
            
            include "services/put.php";
        }

        /*=================
        Peticiones DELETE
        ==============*/

        if($_SERVER['REQUEST_METHOD']== "DELETE"){
            
            include "services/delete.php";
        }
    
    }
?>