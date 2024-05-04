<?php

header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); 
header("Allow: GET, POST, OPTIONS, PUT, DELETE");


require_once "models/get.model.php";
require_once "models/post.model.php";
require_once "models/put.model.php";
require_once "models/connection.php";
require_once "vendor/autoload.php";
use Firebase\JWT\JWT; //Inicializamos la clase para poder usar el componente JWT

class PostController{

    /*==========================================
    Petición post para crear datos
    ==========================================*/

    static public function postData($table,$data){

        $response = PostModel::postData($table,$data);

        $return = new PostController();
        $return -> fncResponse($response, null,null);


    }

    /*==========================================
    Petición post para registrar usuarios
    ==========================================*/

    static public function postRegister($table, $data, $suffix) {

        if (isset($data["password_" . $suffix]) && $data["password_" . $suffix] != null) {

            $data["password_" . $suffix] = password_hash($data["password_" . $suffix], PASSWORD_DEFAULT);
    
            $response = PostModel::postData($table, $data);
    
            $return = new PostController();
            $return->fncResponse($response, null, $suffix);
        }
    }

    /*==========================================
    Petición post para login de usuarios
    ==========================================*/

    static public function postLogin($table, $data, $suffix) {

        // Validamos que el usuario existe en la BD por email
        $response = GetModel::getDataFilter($table, "*", "email_" . $suffix, $data["email_" . $suffix], null, null, null, null);
    
        if (!empty($response)) {
    
            // Verificamos la contraseña usando password_verify
            if (password_verify($data["password_" . $suffix], $response[0]->{"password_" . $suffix})) {
    
                // Generar datos para el token
                $tokenData = Connection::jwt($response[0]->{"id_" . $suffix}, $response[0]->{"email_" . $suffix});
                $algorithm = 'HS256';
                $jwt = JWT::encode($tokenData, "dasergdfgvsrwteyhb43", $algorithm);
    
                // Preparar datos para actualizar el usuario con el nuevo token
                $updateData = array(
                    "token_" . $suffix => $jwt,
                    "token_exp_" . $suffix => $tokenData["exp"]
                );
    
                // Actualizamos la base de datos con el token del usuario
                $update = PutModel::PutData($table, $updateData, $response[0]->{"id_" . $suffix}, "id_" . $suffix);
    
                if (isset($update["comment"]) && $update["comment"] == "El proceso se ha ejecutado correctamente") {
                    $response[0]->{"token_" . $suffix} = $jwt;
                    $response[0]->{"token_exp_" . $suffix} = $tokenData["exp"];
    
                    $return = new PostController();
                    $return->fncResponse($response, null, $suffix);
                }
            } else {
                // Enviar error de contraseña incorrecta
                $response = null;
                $return = new PostController();
                $return->fncResponse($response, "Password incorrecta", $suffix);
            }
        } else {
            // Enviar error si no se encuentra el usuario
            $response = null;
            $return = new PostController();
            $return->fncResponse($response, "No se encuentra este usuario", $suffix);
        }
    }


    

    
    /*==========================================
    Respuesta del controlador
    ==========================================*/

    public function fncResponse($response, $error,$suffix){

        if(!empty($response)){

        /*==========================================
        Quitamos la respuesta de la contraseña
        ==========================================*/

        if(isset($response[0]->{"password_".$suffix})){

            unset($response[0]->{"password_".$suffix});
        }

            $json = array(
                'status' => 200,
                'results' => $response
            ); 

        }else{

            if($error != null){
                $json = array(
                    'status' => 404,
                    'results' => $error,
                    ); 


            }else{

                $json = array(
                    'status' => 404,
                    'results' => 'Not Found',
                    'method' => 'POST'
                    ); 
            }

        }

        echo json_encode($json, http_response_code($json['status']));
    }
}

