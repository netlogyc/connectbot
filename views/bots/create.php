<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Crear bot - Connectbot') ?></title>
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
    <h1 class="h3 mb-3">Crear nuevo bot</h1>

    <form method="post" action="/bots">
        <div class="mb-3">
            <label for="name" class="form-label">Nombre del bot</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" required>
            <div class="form-text">Identificador único del bot (por ejemplo, "demo-netlogyc").</div>
        </div>
        <div class="mb-3">
            <label for="webhook_url" class="form-label">Webhook de n8n</label>
            <input type="url" name="webhook_url" id="webhook_url" class="form-control" placeholder="https://ian8nwebhook.netlogyc.org/webhook/.../chat">
            <div class="form-text">URL del flujo de n8n que gestionará las respuestas de este bot.</div>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="/bots" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>

</body>
</html>
