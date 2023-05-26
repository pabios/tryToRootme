<?php

require 'vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute(['GET', 'POST'], '/api/signup', '\Pabiosoft\Controller\UserController::newUser');
    $r->addRoute(['GET', 'POST'], '/api/signin', '\Pabiosoft\Controller\UserController::login');

    $r->addRoute('GET', '/api/users', '\Pabiosoft\Controller\UserController::users');
    $r->addRoute('GET', '/api/user/delete', '\Pabiosoft\Controller\UserController::deleteUser');
    $r->addRoute('GET', '/api/user/{id:\d+}', '\Pabiosoft\Controller\UserController::getUserById');

});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

if($routeInfo[0] == FastRoute\Dispatcher::FOUND){
    // je verifie si mon parametre est une chaine de caratere
    if(is_string($routeInfo[1])){
        // si dans la chaine recue on trouve les ::
        if(strpos($routeInfo[1],'::') !== false){
            // on coupe par ::
            $route = explode('::',$routeInfo[1]);
            $method = [new $route[0],$route[1]];
        }else{
            // diretement la chaine
            $method = $routeInfo[1];
        }
        //var_dump($routeInfo[2]);
        call_user_func_array($method,$routeInfo[2]);
    }
}
elseif($routeInfo[0] == FastRoute\Dispatcher::NOT_FOUND){
    header("HTTP/1.0 404 Not Found");
    if(method_exists('\Pabiosoft\Controller\SecurityController','error404')) {
        echo call_user_func([new \Pabiosoft\Controller\SecurityController, 'error404']);
    } else {
        echo '<h1>404 Not Found</h1>';
        exit();
    }
}
elseif($routeInfo[0] == FastRoute\Dispatcher::METHOD_NOT_ALLOWED){
    header("HTTP/1.0 405 Method Not Allowed");
    if(method_exists('\Pabiosoft\Controller\SecurityController','error405')) {
        echo call_user_func([new \Pabiosoft\Controller\SecurityController, 'error405']);
    } else {
        echo '<h1>405 Method Not Allowed</h1>';
        exit();
    }
}