<?php

// Permite peticiones desde cualquier origen hasta que lancemos la fase de Angular.

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400'); 
}

// Envíamos solicitud OPTIONS para probar la disponibilidad del servidor antes de hacer una llamada real

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}




/*
$rutaAutorizada = "http://www.rutaejemplo.com";

if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Verificar si la petición proviene del origen permitido
    if ($_SERVER['HTTP_ORIGIN'] == $rutaAutorizada) {
        header("Access-Control-Allow-Origin: $rutaAutorizada");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400'); // Puede variar según tus necesidades
    }
}

// Envíamos solicitud OPTIONS para probar la disponibilidad del servidor antes de hacer una llamada real
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}
*/
