<?php
    ////////////////////////////////////////////////////////////////
    //NÃO PRECISA SER LTERADO, VERIFICADO TUDO CERTO AQUI, SÓ ADICIONADO SE FOR O CASO//
    //ROTAS DO CONTROLADOR MANAGER - RECEBE PAGINS E SCRIPTS//
    ////////////////////////////////////////////////////////////////

    /* Controlador de rota para Manager, pode ser transformada para AdminController também*/
    $routes->group('manager', ['namespace' => 'App\Controllers\Manager'], function ($routes) {

       $routes->get('/', 'ManagerController::index', ['as' => 'manager']); /* ['as' => 'manager'] Apelido de rota */

       $routes->group('categories', function ($routes) {

         $routes->get('/', 'CategoriesController::index', ['as' => 'categories']);
         $routes->get('get-all', 'CategoriesController::getAllCategories', ['as' => 'categories.get.all']);
         $routes->get('get-info', 'CategoriesController::getCategoryInfo', ['as' => 'categories.get.info']);
         $routes->get('get-parents', 'CategoriesController::getDropdownParents', ['as' => 'categories.parents']);

         $routes->post('create', 'CategoriesController::create', ['as' => 'categories.create']);
         $routes->put('update', 'CategoriesController::update', ['as' => 'categories.update']);

       });
    });
?>