<?php

namespace App\Controllers;

use App\Models\Livro;

// Controller dos livros: recebe as requisições e aciona o Model
class LivroController
{
    // Exibe a listagem de todos os livros
    public function index()
    {
        $model  = new Livro();
        $livros = $model->listarTodos();

        require __DIR__ . '/../Views/livros/listar.php';
    }

    // Exibe o formulário de cadastro
    public function create()
    {
        $model      = new Livro();
        $categorias = $model->listarCategorias();

        require __DIR__ . '/../Views/livros/criar.php';
    }

    // Recebe o POST do formulário e salva o livro
    public function store()
    {
        // Valida os campos obrigatórios
        $titulo = trim($_POST['titulo'] ?? '');
        $autor  = trim($_POST['autor']  ?? '');
        $isbn   = trim($_POST['isbn']   ?? '');

        if ($titulo === '' || $autor === '' || $isbn === '') {
            $_SESSION['erro'] = 'Título, autor e ISBN são obrigatórios.';
            header('Location: /livros/novo');
            exit;
        }

        // Monta o array com os dados do formulário
        $dados = [
            'titulo'           => $titulo,
            'autor'            => $autor,
            'isbn'             => $isbn,
            'editora'          => trim($_POST['editora']        ?? ''),
            'ano_publicacao'   => (int) ($_POST['ano_publicacao']   ?? 0) ?: null,
            'categoria_id'     => (int) ($_POST['categoria_id']     ?? 0) ?: null,
            'total_exemplares' => max(1, (int) ($_POST['total_exemplares'] ?? 1)),
        ];

        // Salva no banco e redireciona
        $model = new Livro();
        $model->inserir($dados);

        $_SESSION['sucesso'] = 'Livro cadastrado com sucesso!';
        header('Location: /livros');
        exit;
    }
}
