<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Connectbot') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Connectbot</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/bots">Bots</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-3">Panel de Connectbot</h1>
            <p class="lead">Desde aquí vas a gestionar bots, fuentes (URLs) y conexiones con n8n y Evolution API.</p>
            <a href="/bots" class="btn btn-primary">Gestionar bots</a>
        </div>
    </div>
</div>

</body>
</html>
