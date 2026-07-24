<?php

use App\Controllers\AuthController;
use App\Controllers\LivroController;
use App\Controllers\EmprestimoController;

// rotas da aplicação
// $router é injetado pelo index.php

// -- autenticação --
$router->get('/login',  [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// -- dashboard --
$router->get('/', function () {
    require ROOT . '/app/Views/dashboard/index.php';
});

// -- livros --
$router->get('/livros',               [LivroController::class, 'index']);
$router->get('/livros/novo',          [LivroController::class, 'create']);
$router->post('/livros',              [LivroController::class, 'store']);
$router->get('/livros/{id}',          [LivroController::class, 'show']);
$router->get('/livros/{id}/editar',   [LivroController::class, 'edit']);
$router->post('/livros/{id}/editar',  [LivroController::class, 'update']);
$router->post('/livros/{id}/excluir', [LivroController::class, 'destroy']);

// -- empréstimos --
$router->get('/emprestimos',                 [EmprestimoController::class, 'index']);
$router->get('/meus-emprestimos',            [EmprestimoController::class, 'meus']);
$router->get('/emprestimos/novo',            [EmprestimoController::class, 'create']);
$router->post('/emprestimos',                [EmprestimoController::class, 'store']);
$router->get('/emprestimos/{id}',            [EmprestimoController::class, 'show']);
$router->post('/emprestimos/{id}/devolver',  [EmprestimoController::class, 'devolver']);
$router->post('/emprestimos/{id}/renovar',   [EmprestimoController::class, 'renovar']);
