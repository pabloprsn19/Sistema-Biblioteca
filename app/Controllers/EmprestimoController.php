<?php

namespace App\Controllers;

use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\Usuario;

class EmprestimoController
{
    // Lista todos os empréstimos (atendente/admin) com filtro de status
    public function index()
    {
        $status    = $_GET['status'] ?? '';
        $model     = new Emprestimo();
        $emprestimos = $model->listarTodos($status);
        $totalAtivos   = $model->totalAtivos();
        $totalAtrasados = $model->totalAtrasados();
        require __DIR__ . '/../Views/emprestimos/index.php';
    }

    // Meus empréstimos (leitor)
    public function meus()
    {
        if (empty($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'leitor') {
            header('Location: /login');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];

        $modelUsuario = new Usuario();
        $leitor       = $modelUsuario->buscarPorId($usuarioId);

        $modelEmprestimo = new Emprestimo();
        $emprestimos     = $modelEmprestimo->listarPorUsuario($usuarioId);

        require __DIR__ . '/../Views/emprestimos/meus.php';
    }

    // Detalhes de um empréstimo
    public function show(int $id)
    {
        $model      = new Emprestimo();
        $emprestimo = $model->buscarPorId($id);

        if (!$emprestimo) {
            $_SESSION['erro'] = 'Empréstimo não encontrado.';
            header('Location: /emprestimos');
            exit;
        }

        require __DIR__ . '/../Views/emprestimos/show.php';
    }

    // Formulário para registrar um empréstimo novo (atendente)
    public function create()
    {
        $modelLivro   = new Livro();
        $modelUsuario = new Usuario();
        $livros       = $modelLivro->listarTodos();
        $leitores     = array_filter($modelUsuario->listarTodos(), fn($u) => $u['perfil'] === 'leitor');
        require __DIR__ . '/../Views/emprestimos/create.php';
    }

    // Atendente registra empréstimo para um leitor
    public function store()
    {
        $usuarioId = (int) ($_POST['usuario_id'] ?? 0);
        $livroId   = (int) ($_POST['livro_id']   ?? 0);

        if ($usuarioId <= 0 || $livroId <= 0) {
            $_SESSION['erro'] = 'Selecione um leitor e um livro.';
            header('Location: /emprestimos/novo');
            exit;
        }

        // Verificar suspensão do leitor
        $modelUsuario = new Usuario();
        $leitor       = $modelUsuario->buscarPorId($usuarioId);

        if (!$leitor) {
            $_SESSION['erro'] = 'Leitor não encontrado.';
            header('Location: /emprestimos/novo');
            exit;
        }

        if (!empty($leitor['suspenso_ate']) && strtotime($leitor['suspenso_ate']) > time()) {
            $_SESSION['erro'] = 'Este leitor está suspenso até ' . date('d/m/Y', strtotime($leitor['suspenso_ate'])) . '.';
            header('Location: /emprestimos/novo');
            exit;
        }

        // Verificar disponibilidade do livro
        $modelLivro = new Livro();
        $livro      = $modelLivro->buscarPorId($livroId);
        $livros     = $modelLivro->listarTodos();
        $livroDisp  = null;
        foreach ($livros as $l) {
            if ($l['id'] == $livroId) { $livroDisp = $l; break; }
        }

        if (!$livroDisp || $livroDisp['disponiveis'] <= 0) {
            $_SESSION['erro'] = 'Não há exemplares disponíveis deste livro.';
            header('Location: /emprestimos/novo');
            exit;
        }

        $atendenteId     = $_SESSION['usuario_id'];
        $modelEmprestimo = new Emprestimo();
        $ok              = $modelEmprestimo->emprestar($usuarioId, $livroId, $atendenteId);

        if ($ok) {
            $_SESSION['sucesso'] = 'Empréstimo registrado com sucesso!';
            header('Location: /emprestimos');
        } else {
            $_SESSION['erro'] = 'Erro ao registrar o empréstimo.';
            header('Location: /emprestimos/novo');
        }
        exit;
    }

    // Devolução pelo atendente (via ID do empréstimo)
    public function devolverPorId(int $id)
    {
        $model = new Emprestimo();
        $ok    = $model->devolver($id);

        if ($ok) {
            $_SESSION['sucesso'] = 'Livro devolvido com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao registrar a devolução ou empréstimo já encerrado.';
        }

        header('Location: /emprestimos');
        exit;
    }

    // Leitor pega livro emprestado diretamente do catálogo
    public function emprestarDireto($livroId)
    {
        if (empty($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'leitor') {
            header('Location: /login');
            exit;
        }

        $usuarioId    = $_SESSION['usuario_id'];
        $modelUsuario = new Usuario();
        $leitor       = $modelUsuario->buscarPorId($usuarioId);

        // Checar suspensão
        if (!empty($leitor['suspenso_ate']) && strtotime($leitor['suspenso_ate']) > time()) {
            $_SESSION['erro'] = 'Sua conta está suspensa até ' . date('d/m/Y', strtotime($leitor['suspenso_ate'])) . '. Não é possível pegar livros emprestados.';
            header('Location: /livros');
            exit;
        }

        $modelLivro = new Livro();
        $livros     = $modelLivro->listarTodos();
        $livro      = null;
        foreach ($livros as $l) {
            if ($l['id'] == $livroId) { $livro = $l; break; }
        }

        if (!$livro || $livro['disponiveis'] <= 0) {
            $_SESSION['erro'] = 'Livro não disponível para empréstimo no momento.';
            header('Location: /livros');
            exit;
        }

        $modelEmprestimo = new Emprestimo();
        $ok              = $modelEmprestimo->emprestar($usuarioId, $livroId);

        if ($ok) {
            $_SESSION['sucesso'] = 'Livro pego emprestado com sucesso! Prazo: 14 dias.';
        } else {
            $_SESSION['erro'] = 'Erro ao registrar o empréstimo.';
        }

        header('Location: /livros');
        exit;
    }

    // Leitor devolve livro diretamente do catálogo/meus empréstimos
    public function devolverDireto($livroId)
    {
        if (empty($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'leitor') {
            header('Location: /login');
            exit;
        }

        $usuarioId       = $_SESSION['usuario_id'];
        $modelEmprestimo = new Emprestimo();
        $ok              = $modelEmprestimo->devolverPorLivro($livroId, $usuarioId);

        if ($ok) {
            $_SESSION['sucesso'] = 'Livro devolvido com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao registrar a devolução.';
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '/livros';
        header("Location: $referer");
        exit;
    }

    // Renovar prazo
    public function renovar(int $id)
    {
        $model = new Emprestimo();
        $ok    = $model->renovar($id);

        if ($ok) {
            $_SESSION['sucesso'] = 'Prazo renovado com sucesso por mais 7 dias!';
        } else {
            $_SESSION['erro'] = 'Não foi possível renovar. Verifique o limite de renovações (máx. 2) ou se o empréstimo está ativo.';
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '/meus-emprestimos';
        header("Location: $referer");
        exit;
    }
}
