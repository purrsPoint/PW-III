<?php

    require_once __DIR__ . "/../app/controllers/PartidaController.php";

    $path = parse_url($_SERVER['REQUEST_URI'], PHO_URL_PATH);
    $method - $_SERVER['REQUEST_METHOD'];

    $partidaController = new PartidaController();

    if($path === '/' || $path === '/partidas'){
        $partidaController->index();
    }elseif($path === '/partidas/show'){
        $partidaController->show();
    }elseif($path === '/partidas/create' && method === 'GET'){
        $partidaController->createForm();
    }
?>