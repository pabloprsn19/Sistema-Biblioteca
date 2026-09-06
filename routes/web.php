<?php

use App\Controllers\AuthController;
use App\Controllers\LivroController;
use App\Controllers\EmprestimoController;
use App\Controllers\UsuarioController;
use App\Controllers\RelatorioController;

// ─── Rotas públicas ────────────────────────────────────────────────────────
$router->get('/login',  [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);

// ─── Autenticado (qualquer perfil) ─────────────────────────────────────────
$router->get('/logout', [AuthController::class, 'logout'], 'auth');

$router->get('/', function () {
    require ROOT . '/app/Views/dashboard/index.php';
}, 'auth');

// Catálogo (todos autenticados veem)
$router->get('/livros', [LivroController::class, 'index'], 'auth');

// ─── Staff (atendente + administrador) ─────────────────────────────────────

// Livros
$router->get('/livros/novo',          [LivroController::class, 'create'],  'staff');
$router->post('/livros',              [LivroController::class, 'store'],   'staff');
$router->get('/livros/{id}/editar',   [LivroController::class, 'edit'],    'staff');
$router->post('/livros/{id}/editar',  [LivroController::class, 'update'],  'staff');
$router->get('/livros/{id}/excluir',  [LivroController::class, 'destroy'], 'staff');
$router->post('/livros/{id}/excluir', [LivroController::class, 'destroy'], 'staff');

// Empréstimos (gestão pelo atendente/admin)
$router->get('/emprestimos',              [EmprestimoController::class, 'index'],        'staff');
$router->get('/emprestimos/novo',         [EmprestimoController::class, 'create'],       'staff');
$router->post('/emprestimos',             [EmprestimoController::class, 'store'],        'staff');
$router->get('/emprestimos/{id}',         [EmprestimoController::class, 'show'],         'staff');
$router->get('/emprestimos/{id}/devolver',  [EmprestimoController::class, 'devolverPorId'], 'staff');
$router->post('/emprestimos/{id}/devolver', [EmprestimoController::class, 'devolverPorId'], 'staff');
$router->get('/emprestimos/{id}/renovar',   [EmprestimoController::class, 'renovar'],      'staff');
$router->post('/emprestimos/{id}/renovar',  [EmprestimoController::class, 'renovar'],      'staff');

// ─── Relatórios ────────────────────────────────────────────────────────────
$router->get('/relatorios', [RelatorioController::class, 'index'], 'staff');

// ─── Administrador ─────────────────────────────────────────────────────────

// Usuários
$router->get('/usuarios',                  [UsuarioController::class, 'index'],    'admin');
$router->get('/usuarios/novo',             [UsuarioController::class, 'create'],   'admin');
$router->post('/usuarios',                 [UsuarioController::class, 'store'],    'admin');
$router->get('/usuarios/{id}/editar',      [UsuarioController::class, 'edit'],     'admin');
$router->post('/usuarios/{id}/editar',     [UsuarioController::class, 'update'],   'admin');
$router->post('/usuarios/{id}/suspender',  [UsuarioController::class, 'suspender'], 'admin');
$router->post('/usuarios/{id}/reativar',   [UsuarioController::class, 'reativar'],  'admin');
$router->post('/usuarios/{id}/desativar',  [UsuarioController::class, 'desativar'], 'admin');

// ─── Leitor ────────────────────────────────────────────────────────────────
$router->get('/meus-emprestimos',               [EmprestimoController::class, 'meus'],           'leitor');
$router->get('/livros/{id}/emprestar',          [EmprestimoController::class, 'emprestarDireto'], 'leitor');
$router->post('/livros/{id}/emprestar',         [EmprestimoController::class, 'emprestarDireto'], 'leitor');
$router->get('/livros/{id}/devolver',           [EmprestimoController::class, 'devolverDireto'],  'leitor');
$router->post('/livros/{id}/devolver',          [EmprestimoController::class, 'devolverDireto'],  'leitor');
$router->get('/meus-emprestimos/{id}/renovar',  [EmprestimoController::class, 'renovar'],        'leitor');
$router->post('/meus-emprestimos/{id}/renovar', [EmprestimoController::class, 'renovar'],        'leitor');
