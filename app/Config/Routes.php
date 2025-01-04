<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->SetAutoRoute(false); //Apenas as rotas existentes serão acessadas, sem pular para pagina.

$routes->get('/', 'Home::index', ['as' => 'home']);

/* rotas para o manager */
if(file_exists($manager = ROOTPATH . 'routes/manager.php')) {
    require $manager;
};

/* rotas para API REST */
if(file_exists($api = ROOTPATH . 'routes/api.php')) {
    require $api;
};