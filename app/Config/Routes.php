<?php

namespace Config;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AuthController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Routes publik
$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::index');
$routes->post('/auth/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');
$routes->post('/set-theme', 'AuthController::setTheme');

// Group dengan filter auth
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'AuthController::dashboard');
    
    // Notes
    $routes->get('/notes', 'NoteController::index');
    $routes->get('/notes/create', 'NoteController::create');
    $routes->post('/notes/store', 'NoteController::store');
    $routes->get('/notes/edit/(:num)', 'NoteController::edit/$1');
    $routes->post('/notes/update/(:num)', 'NoteController::update/$1');
    $routes->get('/notes/delete/(:num)', 'NoteController::delete/$1');
    $routes->get('/notes/view/(:num)', 'NoteController::view/$1');
    $routes->get('/notes/download-media/(:num)/(:segment)', 'NoteController::downloadMedia/$1/$2');
    $routes->get('/notes/export', 'NoteController::export');
    
    // Plans
    $routes->get('/plans', 'PlanController::index');
    $routes->get('/plans/create', 'PlanController::create');
    $routes->post('/plans/store', 'PlanController::store');
    $routes->get('/plans/edit/(:num)', 'PlanController::edit/$1');
    $routes->post('/plans/update/(:num)', 'PlanController::update/$1');
    $routes->get('/plans/delete/(:num)', 'PlanController::delete/$1');
    $routes->get('/plans/export', 'PlanController::export');
    $routes->get('/plans/complete/(:num)', 'PlanController::complete/$1');
    $routes->get('/plans/uncomplete/(:num)', 'PlanController::uncomplete/$1');
    $routes->get('/plans/view/(:num)', 'PlanController::view/$1'); // <-- TAMBAH INI
});
