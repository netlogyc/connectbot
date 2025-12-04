<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Bots - Connectbot') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="../public/index.php">Connectbot</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="../public/index.php?route=bots">Bots</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Bots</h1>
        <a href="../public/index.php?route=bots/create" class="btn btn-primary">Nuevo bot</a>
    </div>

    <?php if (empty($bots)): ?>
        <div class="alert alert-info">Todavía no hay bots creados.</div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Webhook n8n</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bots as $bot): ?>
                <tr>
                    <td><?= (int)$bot['id'] ?></td>
                    <td><?= htmlspecialchars($bot['name']) ?></td>
                    <td><?= htmlspecialchars($bot['slug']) ?></td>
                    <td><?= htmlspecialchars($bot['webhook_url'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
