<?php
// public/index.php
// Front controller básico para el panel Connectbot

declare(strict_types=1);

$installedFlag = __DIR__ . '/../storage/.installed';
if (!file_exists($installedFlag)) {
    header('Location: install.php');
    exit;
}

require __DIR__ . '/../src/bootstrap.php';

use Netlogyc\Connectbot\Router;

$router = new Router();

// Rutas de panel
$router->get('/', 'DashboardController@index');
$router->get('/bots', 'BotController@index');
$router->get('/bots/create', 'BotController@create');
$router->post('/bots', 'BotController@store');

// Endpoint API de chat web
$router->post('/api/chat', 'ChatController@handleWebChat');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
