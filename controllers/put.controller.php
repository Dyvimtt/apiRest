<?php

require_once "models/put.model.php";

class PutController{

    /*==========================================
    Petición PUT para editar datos
    ==========================================*/

    static public function putData($table,$data,$id,$nameId){

        $response = PutModel::putData($table,$data,$id,$nameId);
        echo '<pre>'; print_r($response); echo '</pre>';
        return;

        $return = new PutController();
        $return -> fncResponse($response);


    }

    
    /*==========================================
    Respuesta del controlador
    ==========================================*/

    public function fncResponse($response){

        if(!empty($response)){

            $json = array(
                'status' => 200,
                'results' => $response
            ); 

        }else{
            $json = array(
                'status' => 404,
                'results' => 'Not Found',
                'method' => 'PUT'
                ); 
        }

        echo json_encode($json, http_response_code($json['status']));
    }
}