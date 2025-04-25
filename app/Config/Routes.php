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
            $routes->get('form', 'Entrada::formularioMultiplas', ['as' => 'restrito.entrada.formularioMultiplas']);
            $routes->post('salvar-multiplas', 'Entrada::salvarMultiplas', ['as' => 'restrito.entrada.salvarMultiplas']);

            // Por produto
            $routes->get('(:any)/listar', 'Entrada::index/$1', ['as' => 'restrito.entrada.listar']);
            $routes->get('(:any)/form', 'Entrada::formulario/$1', ['as' => 'restrito.entrada.formulario']);
            $routes->get('(:any)/editar/(:any)', 'Entrada::formulario/$1/$2', ['as' => 'restrito.entrada.editar']);
            $routes->post('salvar', 'Entrada::salvar', ['as' => 'restrito.entrada.salvar']);
            $routes->post('excluir', 'Entrada::excluir', ['as' => 'restrito.entrada.excluir']);
        });

         // Saídas    
         $routes->group('out', function ($routes) {
            // Em lote
            $routes->get('/', 'Saida::index', ['as' => 'restrito.saida.index']);
            $routes->get('form', 'Saida::formularioMultiplas', ['as' => 'restrito.saida.formularioMultiplas']);
            $routes->post('salvar-multiplas', 'Saida::salvarMultiplas', ['as' => 'restrito.saida.salvarMultiplas']);

            // Por produto
            $routes->get('(:any)/listar', 'Saida::index/$1', ['as' => 'restrito.saida.listar']);
            $routes->get('(:any)/form', 'Saida::formulario/$1', ['as' => 'restrito.saida.formulario']);
            $routes->get('(:any)/editar/(:any)', 'Saida::formulario/$1/$2', ['as' => 'restrito.saida.editar']);
            $routes->post('salvar', 'Saida::salvar', ['as' => 'restrito.saida.salvar']);
            $routes->post('excluir', 'Saida::excluir', ['as' => 'restrito.saida.excluir']);
        });
        
    });


    // Ref Categoria
    $routes->post('salvar', 'RefCategoria::salvar', ['as' => 'restrito.refCategoria.salvar']);

    // Relatórios    
    $routes->group('relatorios', static function ($routes) {
        $routes->get('/', 'Relatorio::index', ['as' => 'restrito.relatorio.index']);

        $routes->get('saldo-estoque', 'Relatorio::saldoEstoque', ['as' => 'restrito.relatorio.saldoEstoque']);
        $routes->get('movimento', 'Relatorio::movimentacaoProduto', ['as' => 'restrito.relatorio.movimentacaoProduto']);
        
    });

});