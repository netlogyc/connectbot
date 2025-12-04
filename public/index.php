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

// Normalizar URI a partir de querystring ?route= cuando se usa index.php en subcarpetas
$uri = $_SERVER['REQUEST_URI'];
if (isset($_GET['route']) && $_GET['route'] !== '') {
    $uri = '/' . ltrim((string)$_GET['route'], '/');
}

$router->dispatch($uri, $_SERVER['REQUEST_METHOD']);
