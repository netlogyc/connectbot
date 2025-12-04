<?php
// src/Http/Controllers/DashboardController.php

namespace Netlogyc\Connectbot\Http\Controllers;

class DashboardController
{
    public function index(): void
    {
        $title = 'Connectbot - Panel';
        require __DIR__ . '/../../../views/dashboard.php';
    }
}
