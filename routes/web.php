<?php

use App\Controllers\AuthController;
use App\Controllers\LivroController;
use App\Controllers\EmprestimoController;

// Rotas públicas
$router->get('/login',  [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);

// Requer autenticação
$router->get('/logout', [AuthController::class, 'logout'], 'auth');

$router->get('/', function () {
    require ROOT . '/app/Views/dashboard/index.php';
}, 'auth');

$router->get('/livros', [LivroController::class, 'index'], 'auth');

// Requer autenticação + perfil não-leitor (atendente ou administrador)
$router->get('/livros/novo',          [LivroController::class, 'create'],  'staff');
$router->post('/livros',              [LivroController::class, 'store'],   'staff');
$router->get('/livros/{id}/editar',   [LivroController::class, 'edit'],    'staff');
$router->post('/livros/{id}/editar',  [LivroController::class, 'update'],  'staff');
$router->post('/livros/{id}/excluir', [LivroController::class, 'destroy'], 'staff');

// Requer autenticação + perfil leitor
$router->get('/meus-emprestimos',        [EmprestimoController::class, 'meus'],           'leitor');
$router->post('/livros/{id}/emprestar',  [EmprestimoController::class, 'emprestarDireto'], 'leitor');
$router->post('/livros/{id}/devolver',   [EmprestimoController::class, 'devolverDireto'],  'leitor');
