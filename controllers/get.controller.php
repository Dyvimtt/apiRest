
<?php

require_once "models/get.model.php";
//Este es el controlador del método GET

class GetController{

    //PETICIÓN GET SIN FILTRO - CONTROLADOR

    static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt){

        $response = GetModel::getData($table, $select, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);

    }

    //PETICIÓN GET CON FILTRO - CONTROLADOR

    static public function getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){

        $response = GetModel::getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);

    }

    //PETICIÓN GET SIN FILTRO ENTRE TABLAS RELACIONADAS

    static public function getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt){

        $response = GetModel::getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);
    
    }

    //PETICIÓN GET CON FILTRO ENTRE TABLAS RELACIONADAS

    static public function getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){

        $response = GetModel::getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);
        
    }
    
    //PETICIÓN GET PARA EL BUSCADOR SIN RELACIONES

    static public function getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){

        $response = GetModel::getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);

    }

    // RESPUESTA DEL CONTROLADOR

    public function fncResponse($response){

        if(!empty($response)){

            $json = array(
                'status' => 200,
                'total' => count($response),
                'results' => $response
            ); 

        }else{
            $json = array(
                'status' => 404,
                'results' => 'Not Found'
                ); 
        }

        echo json_encode($json, http_response_code($json['status']));
    }

}