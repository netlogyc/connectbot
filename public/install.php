<?php
// public/install.php
// Instalador inicial de Connectbot para configurar la base de datos

declare(strict_types=1);

$installedFlag = __DIR__ . '/../storage/.installed';
$configFile   = __DIR__ . '/../config/config.php';

if (file_exists($installedFlag)) {
    http_response_code(403);
    echo 'La plataforma ya está instalada.';
    exit;
}

$error = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = trim($_POST['db_host'] ?? '');
    $dbName = trim($_POST['db_name'] ?? '');
    $dbUser = trim($_POST['db_user'] ?? '');
    $dbPass = (string)($_POST['db_pass'] ?? '');
    $dbPort = trim($_POST['db_port'] ?? '3306');

    if ($dbHost === '' || $dbName === '' || $dbUser === '') {
        $error = 'Todos los campos excepto la contraseña son obligatorios.';
    } else {
        try {
            $dsn = 'mysql:host=' . $dbHost . ';port=' . $dbPort . ';dbname=' . $dbName . ';charset=utf8mb4';
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            // Crear tablas básicas (solo bots por ahora)
            $sql = "CREATE TABLE IF NOT EXISTS bots (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $pdo->exec($sql);

            // Asegurar directorios
            @mkdir(__DIR__ . '/../config', 0755, true);
            @mkdir(__DIR__ . '/../storage', 0755, true);

            // Generar archivo de configuración
            $configPhp = "<?php\n\nreturn [\n    'db_host' => '" . addslashes($dbHost) . "',\n    'db_port' => '" . addslashes($dbPort) . "',\n    'db_name' => '" . addslashes($dbName) . "',\n    'db_user' => '" . addslashes($dbUser) . "',\n    'db_pass' => '" . addslashes($dbPass) . "',\n];\n";

            if (file_put_contents($configFile, $configPhp) === false) {
                $error = 'No se pudo escribir el archivo de configuración en config/config.php. Verifica permisos.';
            } else {
                // Crear flag de instalación
                file_put_contents($installedFlag, date('c'));
                $success = true;
            }
        } catch (Throwable $e) {
            $error = 'No se pudo conectar a la base de datos: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instalación de Connectbot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h1 class="h5 mb-0">Instalación inicial de Connectbot</h1>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            Instalación completada correctamente. Ahora puedes eliminar o proteger <code>public/install.php</code> y acceder al panel principal.
                        </div>
                        <a href="./" class="btn btn-primary">Ir al panel</a>
                    <?php else: ?>
                        <p class="mb-3">Introduce los datos de conexión a tu base de datos MariaDB/MySQL. Estos datos se guardarán en <code>config/config.php</code>.</p>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="db_host" class="form-label">Host</label>
                                <input type="text" name="db_host" id="db_host" class="form-control" required value="<?= htmlspecialchars($_POST['db_host'] ?? 'localhost') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="db_port" class="form-label">Puerto</label>
                                <input type="text" name="db_port" id="db_port" class="form-control" required value="<?= htmlspecialchars($_POST['db_port'] ?? '3306') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="db_name" class="form-label">Nombre de la base de datos</label>
                                <input type="text" name="db_name" id="db_name" class="form-control" required value="<?= htmlspecialchars($_POST['db_name'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="db_user" class="form-label">Usuario</label>
                                <input type="text" name="db_user" id="db_user" class="form-control" required value="<?= htmlspecialchars($_POST['db_user'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="db_pass" class="form-label">Contraseña</label>
                                <input type="password" name="db_pass" id="db_pass" class="form-control">
                            </div>
                            <button type="submit" class="btn btn成功">Instalar</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
