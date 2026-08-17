<?php

namespace App\Controllers;

// tudo que envolve empréstimos e devoluções 

class EmprestimoController
{
    // lista todos os empréstimos (atendente/admin)
    public function index()
    {
        // TODO: filtrar por status (ativo, devolvido, atrasado)
        require __DIR__ . '/../Views/emprestimos/index.php';
    }

    public function meus()
    {
        if (empty($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'leitor') {
            header('Location: /login');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];

        $modelUsuario = new \App\Models\Usuario();
        $leitor = $modelUsuario->buscarPorId($usuarioId);

        $modelEmprestimo = new \App\Models\Emprestimo();
        $emprestimos = $modelEmprestimo->listarPorUsuario($usuarioId);

        require __DIR__ . '/../Views/emprestimos/meus.php';
    }

    // detalhes de um empréstimo
    public function show($id)
    {
        // TODO: buscar empréstimo pelo id
        require __DIR__ . '/../Views/emprestimos/show.php';
    }

    // formulário pra registrar um empréstimo novo
    public function create()
    {
        // vai precisar listar livros disponíveis e leitores
        require __DIR__ . '/../Views/emprestimos/create.php';
    }

    public function emprestarDireto($livroId)
    {
        if (empty($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'leitor') {
            header('Location: /login');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];

        $modelUsuario = new \App\Models\Usuario();
        $leitor = $modelUsuario->buscarPorId($usuarioId);

        // Checar suspensão
        if (!empty($leitor['suspenso_ate']) && strtotime($leitor['suspenso_ate']) > time()) {
            $_SESSION['erro'] = 'Sua conta está suspensa. Não é possível pegar livros emprestados.';
            header('Location: /livros');
            exit;
        }

        $modelLivro = new \App\Models\Livro();
        $livros = $modelLivro->listarTodos();
        $livro = null;
        foreach ($livros as $l) {
            if ($l['id'] == $livroId) {
                $livro = $l;
                break;
            }
        }

        if (!$livro || $livro['disponiveis'] <= 0) {
            $_SESSION['erro'] = 'Livro não disponível para empréstimo no momento.';
            header('Location: /livros');
            exit;
        }

        $modelEmprestimo = new \App\Models\Emprestimo();
        $ok = $modelEmprestimo->emprestar($usuarioId, $livroId);

        if ($ok) {
            $_SESSION['sucesso'] = 'Livro pego emprestado com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao registrar o empréstimo.';
        }

        header('Location: /livros');
        exit;
    }

    public function devolverDireto($livroId)
    {
        if (empty($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'leitor') {
            header('Location: /login');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];

        $modelEmprestimo = new \App\Models\Emprestimo();
        $ok = $modelEmprestimo->devolverPorLivro($livroId, $usuarioId);

        if ($ok) {
            $_SESSION['sucesso'] = 'Livro devolvido com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao registrar a devolução.';
        }

        // Tenta voltar de onde veio (do Catálogo ou de Meus Empréstimos)
        $referer = $_SERVER['HTTP_REFERER'] ?? '/livros';
        header("Location: $referer");
        exit;
    }

    // renova o prazo
    public function renovar($id)
    {
        // TODO: checar se pode renovar (sem atraso e dentro do limite)
    }
}
