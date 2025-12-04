<?php
// src/Http/Controllers/ChatController.php

namespace Netlogyc\Connectbot\Http\Controllers;

use PDO;

class ChatController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = $GLOBALS['connectbot_pdo'];
    }

    public function handleWebChat(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            http_response_code(400);
            echo json_encode(['error' => 'JSON inválido']);
            return;
        }

        $botSlug = trim($input['bot_slug'] ?? '');
        $message = trim($input['message'] ?? '');
        $sessionId = trim($input['session_id'] ?? '');

        if ($botSlug === '' || $message === '') {
            http_response_code(422);
            echo json_encode(['error' => 'bot_slug y message son obligatorios']);
            return;
        }

        $stmt = $this->pdo->prepare('SELECT id, name, slug, webhook_url FROM bots WHERE slug = :slug LIMIT 1');
        $stmt->execute([':slug' => $botSlug]);
        $bot = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$bot) {
            http_response_code(404);
            echo json_encode(['error' => 'Bot no encontrado']);
            return;
        }

        $webhookUrl = trim($bot['webhook_url'] ?? '');
        if ($webhookUrl === '') {
            http_response_code(500);
            echo json_encode(['error' => 'El bot no tiene webhook_url configurado']);
            return;
        }

        // Construir payload para n8n (puedes adaptarlo al formato que ya usas allí)
        $payload = [
            'bot' => [
                'id' => (int)$bot['id'],
                'name' => $bot['name'],
                'slug' => $bot['slug'],
            ],
            'message' => $message,
            'session_id' => $sessionId !== '' ? $sessionId : bin2hex(random_bytes(8)),
            'source' => 'web',
        ];

        $ch = curl_init($webhookUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        $responseBody = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($responseBody === false) {
            http_response_code(502);
            echo json_encode(['error' => 'Error al llamar al webhook de n8n', 'details' => $curlError]);
            return;
        }

        // Se asume que n8n responde JSON
        $decoded = json_decode($responseBody, true);
        if (!is_array($decoded)) {
            http_response_code($httpCode >= 200 && $httpCode < 300 ? 200 : $httpCode);
            echo json_encode([
                'message' => $responseBody,
                'http_code' => $httpCode,
            ]);
            return;
        }

        http_response_code($httpCode >= 200 && $httpCode < 300 ? 200 : $httpCode);
        echo json_encode($decoded);
    }
}
