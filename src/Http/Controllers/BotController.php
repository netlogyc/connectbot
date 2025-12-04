<?php
// src/Http/Controllers/BotController.php

namespace Netlogyc\Connectbot\Http\Controllers;

use PDO;

class BotController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = $GLOBALS['connectbot_pdo'];
    }

    public function index(): void
    {
        $stmt = $this->pdo->query('SELECT id, name, slug FROM bots ORDER BY id DESC');
        $bots = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $title = 'Bots - Connectbot';
        require __DIR__ . '/../../../views/bots/index.php';
    }

    public function create(): void
    {
        $title = 'Crear bot - Connectbot';
        require __DIR__ . '/../../../views/bots/create.php';
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');

        if ($name === '' || $slug === '') {
            http_response_code(422);
            echo 'Nombre y slug son obligatorios';
            return;
        }

        $stmt = $this->pdo->prepare('INSERT INTO bots (name, slug) VALUES (:name, :slug)');
        $stmt->execute([
            ':name' => $name,
            ':slug' => $slug,
        ]);

        header('Location: /bots');
        exit;
    }
}
