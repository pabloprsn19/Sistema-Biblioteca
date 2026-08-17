<?php

define('ROOT', dirname(__DIR__));

$env = ROOT . '/.env';
if (file_exists($env)) {
    foreach (file($env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        if (str_starts_with(trim($linha), '#') || !str_contains($linha, '=')) continue;
        [$chave, $valor] = explode('=', $linha, 2);
        $_ENV[trim($chave)] = trim($valor);
    }
}

spl_autoload_register(function ($classe) {
    $prefixo = 'App\\';
    $base    = ROOT . '/app/';

    if (!str_starts_with($classe, $prefixo)) return;

    $arquivo = $base . str_replace('\\', '/', substr($classe, strlen($prefixo))) . '.php';

    if (file_exists($arquivo)) require $arquivo;
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new \App\Core\Router();
require ROOT . '/routes/web.php';

$uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->dispatch($metodo, $uri);
