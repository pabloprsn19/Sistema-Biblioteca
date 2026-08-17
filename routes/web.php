<?php

use App\Controllers\AuthController;
use App\Controllers\LivroController;
use App\Controllers\EmprestimoController;

$router->get('/login',  [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/', function () {
    require ROOT . '/app/Views/dashboard/index.php';
});

$router->get('/livros',               [LivroController::class, 'index']);
$router->get('/livros/novo',          [LivroController::class, 'create']);
$router->post('/livros',              [LivroController::class, 'store']);
$router->get('/livros/{id}/editar',   [LivroController::class, 'edit']);
$router->post('/livros/{id}/editar',  [LivroController::class, 'update']);
$router->post('/livros/{id}/excluir', [LivroController::class, 'destroy']);

$router->get('/meus-emprestimos',     [EmprestimoController::class, 'meus']);

$router->post('/livros/{id}/emprestar', [EmprestimoController::class, 'emprestarDireto']);
$router->post('/livros/{id}/devolver',  [EmprestimoController::class, 'devolverDireto']);
