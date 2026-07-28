<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$wsToken = $_ENV['WS_ACCESS_TOKEN'] ?? ($_ENV['APP_KEY'] ?? '');
$validTipos = [3, 4, 5, 6];

$host = '0.0.0.0';
$port = 8080;

$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($server, $host, $port);
socket_listen($server);

$clients = [];
$buffer = [];

echo "Servidor WebSocket corriendo en ws://{$host}:{$port}\n";

function handshake($client) {
}

function decodeFrame($data) {
}

function validateMessage($data, $wsToken, $validTipos) {
}

function encodeFrame($data) {
}

function broadcast($clients, $message, $exclude = null) {
}

while (true) {
}
