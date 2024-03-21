<?php

    require_once "controllers/get.controller.php";

    //Esta es la vista del metodo GET

    /* Recogemos la ruta que nos pasan por el método GET y utilizamos explode para separar la ruta que nos envía y coger solo la parte que hay antes del interrogante
     donde pasaremos los parámetros para hacer consultas específicas. */

    $table = explode("?", $routesArray[1])[0];

    // Para pasar un parametro específico por url de tipo get hay que añadir un ? entre la tabla y lo que queremos consultar.
    // Operador de comparación si no viene ningún valor en la variable será igual a asterisco.
    // Esto lo pasamos como parámetro a la función del modelo y al controlador.

    $select = $_GET["select"] ?? "*" ;

    //Capturamos el orderBy y orderMode para pasarlos como parámetros

    $orderBy = $_GET["orderBy"] ?? null;
    $orderMode = $_GET["orderMode"] ?? null;

    //Capturamos el limit startAt y endAt

    $startAt = $_GET["startAt"] ?? null;
    $endAt = $_GET["endAt"] ?? null;

    // Al hacer los métodos de las clases publicos no hace falta crear un objeto llamamos directamente a la función, para poner un segundo parametro utilizar el símbolo %.
    // apirest.com/libro?select=id,titulo"

    $response = new GetController();

        
    // PETICIÓN GET CON FILTRO, PRIMERO COMPROBAMOS QUE VIENE DENTRO DE LA RUTA EL linkTo Y equalTo

    if(isset($_GET["linkTo"]) && isset($_GET["equalTo"]) && !isset($_GET["rel"]) && !isset($_GET["type"])){

        $response -> getDataFilter($table, $select, $_GET["linkTo"], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt);

    // PETICIÓN GET sin filtro entre tablas relacionadas

    }else if(isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && !isset($_GET["linkTo"]) && !isset($_GET["equalTo"])){

        $response -> getRelData($_GET["rel"], $_GET["type"], $select, $orderBy, $orderMode, $startAt, $endAt);
    
    // PETICIÓN GET con filtros entre tablas relacionadas

    }else if(isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["equalTo"])){

        $response -> getRelDataFilter($_GET["rel"], $_GET["type"], $select, $_GET['linkTo'], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt);

    // PETICIÓN GET para el buscador sin relación
    }else if(isset($_GET["linkTo"]) && isset($_GET["search"])){
        $response -> getDataSearch($table, $select, $_GET["linkTo"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt);
    }else{

        //PETICIÓN GET SIN FILTRO

        $response -> getData($table, $select, $orderBy, $orderMode, $startAt, $endAt);
    }

