<?php

/*
 * index.php - ponto de entrada da aplicação
 *
 * Tudo passa por aqui. O servidor deve apontar o document root
 * pra pasta /public e o .htaccess redireciona tudo pra cá.
 */

// raiz do projeto (pasta acima de /public)
define('ROOT', dirname(__DIR__));

// carrega as variáveis do .env se o arquivo existir
$env = ROOT . '/.env';
if (file_exists($env)) {
    foreach (file($env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        if (str_starts_with(trim($linha), '#') || !str_contains($linha, '=')) continue;
        [$chave, $valor] = explode('=', $linha, 2);
        $_ENV[trim($chave)] = trim($valor);
    }
}

// autoloader PSR-4 manual (sem composer por enquanto)
// App\Controllers\AuthController => /app/Controllers/AuthController.php
spl_autoload_register(function ($classe) {
    $prefixo = 'App\\';
    $base    = ROOT . '/app/';

    if (!str_starts_with($classe, $prefixo)) return;

    $arquivo = $base . str_replace('\\', '/', substr($classe, strlen($prefixo))) . '.php';

    if (file_exists($arquivo)) require $arquivo;
});

// inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// instancia o router e carrega as rotas
$router = new \App\Core\Router();
require ROOT . '/routes/web.php';

// captura a URI sem query string e despacha
$uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->dispatch($metodo, $uri);
