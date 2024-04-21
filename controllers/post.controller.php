<?php
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

    static public function postRegister($table,$data, $suffix){

        if(isset($data["password_".$suffix]) && $data["password_".$suffix] != null){
            $crypt = crypt($data["password_".$suffix], '$2a$07$azybx32s23aasdg23sdfhsd$');
            $data["password_".$suffix] = $crypt;

            $response = PostModel::postData($table,$data);

            $return = new PostController();
            $return -> fncResponse($response, null,$suffix);
        }
    }

    /*==========================================
    Petición post para login de usuarios
    ==========================================*/

    static public function postLogin($table, $data, $suffix){

    /*==========================================
    Validamos que el usuario existe en la BD por email en vez de login tenerlo en cuenta
    ==========================================*/

    $response = GetModel::getDataFilter($table, "*", "email_".$suffix, $data["email_".$suffix],null,null,null,null);

        if(!empty($response)){

            /*==========================================
            Encriptamos la contraseña que viene en Data
            ==========================================*/

            $crypt = crypt($data["password_".$suffix], '$2a$07$azybx32s23aasdg23sdfhsd$');

            if($response[0]->{"password_".$suffix} == $crypt){

                $tokenData = Connection::jwt($response[0]->{"id_".$suffix}, $response[0]->{"email_".$suffix});

                $algorithm = 'HS256';

                // Pasar solo los datos específicos contenidos en $tokenData
                $jwt = JWT::encode($tokenData, "dasergdfgvsrwteyhb43", $algorithm);


                
            /*==========================================
            Actualizamos la base de datos con el token del usuario
            ==========================================*/

                $data = array(
                    "token_".$suffix => $jwt,
                    "token_exp_".$suffix => $tokenData["exp"]
                );

                $update = PutModel::PutData($table,$data,$response[0]->{"id_".$suffix},"id_".$suffix);

                if(isset($update["comment"]) && $update["comment"] == "El proceso se ha ejecutado correctamente"){

                    $response[0] -> {"tokken_".$suffix} = $jwt;
                    $response[0] -> {"tokken_exp_".$suffix} = $tokenData["exp"];

                    $return = new PostController();
                    $return -> fncResponse($response, null,$suffix);


                }

            }else{

                $response = null;
                $return = new PostController();
                $return -> fncResponse($response, "Password incorrecta",$suffix);

            }

        }else{
            $response = null;
            $return = new PostController();
            $return -> fncResponse($response, "No se encuentra este usuario",$suffix);
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

