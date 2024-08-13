<?php

require_once '../config/config.php';function route($uri)
{
    $uri = parse_url($uri, PHP_URL_PATH);

    
    $routes = [
        '/' => 'worksites',
        '/customers' => 'customers',
        '/worksites' => 'worksites',
        '/materials' => 'materials',
        '/payments' => 'payments',
        '/main-storage' => 'main-storage',
    ];

    
    $page = $routes[$uri] ?? 'worksites';

    
    $page = preg_replace('/[^a-zA-Z0-9-_]/', '', $page);

    return $page;
}$page = route($_SERVER['REQUEST_URI']);require_once "../views/layout.php";
