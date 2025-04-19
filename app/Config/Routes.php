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
        $routes->get('editar/(:any)', 'Produto::formulario/$1', ['as' => 'restrito.produto.editar']);
        $routes->post('salvar', 'Produto::salvar', ['as' => 'restrito.produto.salvar']);
        $routes->post('excluir', 'Produto::excluir', ['as' => 'restrito.produto.excluir']);

        // Entradas    
        $routes->group('in', function ($routes) {
            // Em lote
            $routes->get('/', 'Entrada::index', ['as' => 'restrito.entrada.index']);
            $routes->get('form', 'Entrada::form', ['as' => 'restrito.entrada.index']);
            $routes->post('salvar-multiplas', 'Entrada::salvarMultiplas', ['as' => 'restrito.entrada.salvarMultiplas']);

            // Por produto
            $routes->get('(:any)/listar', 'Entrada::index/$1', ['as' => 'restrito.entrada.listar']);
            $routes->get('(:any)/form', 'Entrada::formulario/$1', ['as' => 'restrito.entrada.formulario']);
            $routes->post('salvar', 'Entrada::salvar', ['as' => 'restrito.entrada.salvar']);
        });

        // Saida    
        $routes->group('out', static function ($routes) {
            $routes->get('/', 'Saida::index', ['as' => 'restrito.saida.index']);
            $routes->get('form', 'Saida::formulario', ['as' => 'restrito.saida.formulario']);
        });
        
    });


    // Ref Categoria
    $routes->post('salvar', 'RefCategoria::salvar', ['as' => 'restrito.refCategoria.salvar']);

});