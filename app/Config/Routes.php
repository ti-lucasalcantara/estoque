<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


// Login
$routes->get('login', 'Login::index', ['as' => 'login']);
$routes->post('login', 'Login::entrar', ['as' => 'login']);
$routes->get('sair', 'Login::sair', ['as' => 'sair']);


$routes->group('restrito', ['namespace' => 'App\Controllers\Restrito'], static function ($routes) {
    $routes->get('/', 'Dashboard::index', ['as' => 'restrito.dashboard.index']);

    // produto    
    $routes->group('produto', static function ($routes) {
        $routes->get('/', 'Produto::index', ['as' => 'restrito.produto.index']);
        $routes->get('form', 'Produto::formulario', ['as' => 'restrito.produto.formulario']);

        // Entradas    
        $routes->group('in', static function ($routes) {
            $routes->get('/', 'Entrada::index', ['as' => 'restrito.entrada.index']);
            $routes->get('form', 'Entrada::formulario', ['as' => 'restrito.entrada.formulario']);
        });

        // Saida    
        $routes->group('out', static function ($routes) {
            $routes->get('/', 'Saida::index', ['as' => 'restrito.saida.index']);
            $routes->get('form', 'Saida::formulario', ['as' => 'restrito.saida.formulario']);
        });
        
    });


});