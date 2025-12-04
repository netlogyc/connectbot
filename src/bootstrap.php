<?php
// src/bootstrap.php
// Autoload y conexión a BD usando config/config.php generado por el instalador

declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $prefix = 'Netlogyc\\Connectbot\\';
    $baseDir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$configPath = __DIR__ . '/../config/config.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo 'Configuración no encontrada. Ejecuta primero el instalador.';
    exit;
}

$config = require $configPath;

try {
    $dsn = 'mysql:host=' . $config['db_host'] . ';port=' . $config['db_port'] . ';dbname=' . $config['db_name'] . ';charset=utf8mb4';
    $pdo = new PDO(
        $dsn,
        $config['db_user'],
        $config['db_pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Error de conexión a la base de datos';
    exit;
}

$GLOBALS['connectbot_pdo'] = $pdo;
