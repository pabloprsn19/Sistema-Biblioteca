<?php

namespace App\Controllers;

use App\Models\Livro;

class LivroController
{
    public function index()
    {
        $model  = new Livro();
        $livros = $model->listarTodos();
        $meusEmprestimosAtivos = [];

        if (($_SESSION['usuario_perfil'] ?? '') === 'leitor' && !empty($_SESSION['usuario_id'])) {
            $modelEmprestimo = new \App\Models\Emprestimo();
            $meusEmprestimosAtivos = $modelEmprestimo->buscarAtivosPorUsuario($_SESSION['usuario_id']);
        }

        require __DIR__ . '/../Views/livros/listar.php';
    }

    public function create()
    {
        $model      = new Livro();
        $categorias = $model->listarCategorias();

        require __DIR__ . '/../Views/livros/criar.php';
    }

    public function store()
    {
        $titulo = trim($_POST['titulo'] ?? '');
        $autor  = trim($_POST['autor']  ?? '');
        $isbn   = trim($_POST['isbn']   ?? '');

        if ($titulo === '' || $autor === '' || $isbn === '') {
            $_SESSION['erro'] = 'Título, autor e ISBN são obrigatórios.';
            header('Location: /livros/novo');
            exit;
        }

        $dados = [
            'titulo'           => $titulo,
            'autor'            => $autor,
            'isbn'             => $isbn,
            'editora'          => trim($_POST['editora']        ?? ''),
            'ano_publicacao'   => (int) ($_POST['ano_publicacao']   ?? 0) ?: null,
            'categoria_id'     => (int) ($_POST['categoria_id']     ?? 0) ?: null,
            'total_exemplares' => max(1, (int) ($_POST['total_exemplares'] ?? 1)),
        ];

        $model = new Livro();
        $model->inserir($dados);

        $_SESSION['sucesso'] = 'Livro cadastrado com sucesso!';
        header('Location: /livros');
        exit;
    }

    public function edit(int $id)
    {
        $model = new Livro();
        $livro = $model->buscarPorId($id);

        if (!$livro) {
            $_SESSION['erro'] = 'Livro não encontrado.';
            header('Location: /livros');
            exit;
        }

        $categorias = $model->listarCategorias();

        require __DIR__ . '/../Views/livros/editar.php';
    }

    public function update(int $id)
    {
        $titulo = trim($_POST['titulo'] ?? '');
        $autor  = trim($_POST['autor']  ?? '');
        $isbn   = trim($_POST['isbn']   ?? '');

        if ($titulo === '' || $autor === '' || $isbn === '') {
            $_SESSION['erro'] = 'Título, autor e ISBN são obrigatórios.';
            header("Location: /livros/{$id}/editar");
            exit;
        }

        $dados = [
            'titulo'           => $titulo,
            'autor'            => $autor,
            'isbn'             => $isbn,
            'editora'          => trim($_POST['editora']        ?? ''),
            'ano_publicacao'   => (int) ($_POST['ano_publicacao']   ?? 0) ?: null,
            'categoria_id'     => (int) ($_POST['categoria_id']     ?? 0) ?: null,
            'total_exemplares' => max(1, (int) ($_POST['total_exemplares'] ?? 1)),
        ];

        $model = new Livro();
        $ok    = $model->atualizar($id, $dados);

        if ($ok) {
            $_SESSION['sucesso'] = 'Livro atualizado com sucesso!';
            header('Location: /livros');
        } else {
            $_SESSION['erro'] = 'Erro ao atualizar o livro. Tente novamente.';
            header("Location: /livros/{$id}/editar");
        }
        exit;
    }

    public function destroy(int $id)
    {
        $model = new Livro();
        $ok    = $model->excluir($id);

        if ($ok) {
            $_SESSION['sucesso'] = 'Livro excluído com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao excluir o livro. Tente novamente.';
        }

        header('Location: /livros');
        exit;
    }
}
