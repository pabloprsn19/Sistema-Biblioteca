<?php

namespace App\Controllers;

// controller do catálogo de livros
// CRUD básico: listar, ver, criar, editar e remover

class LivroController
{
    // listagem de todos os livros
    public function index()
    {
        // TODO: buscar livros no banco e passar pra view
        require __DIR__ . '/../Views/livros/index.php';
    }

    // detalhes de um livro específico
    public function show($id)
    {
        // TODO: buscar livro pelo id
        require __DIR__ . '/../Views/livros/show.php';
    }

    // formulário de cadastro
    public function create()
    {
        require __DIR__ . '/../Views/livros/create.php';
    }

    // salva o livro novo no banco
    public function store()
    {
        // TODO: validar os dados do formulário
        // TODO: inserir no banco e redirecionar
    }

    // formulário de edição
    public function edit($id)
    {
        // TODO: buscar o livro pelo id antes de exibir o form
        require __DIR__ . '/../Views/livros/edit.php';
    }

    // atualiza os dados do livro
    public function update($id)
    {
        // TODO: validar e salvar as alterações
    }

    // remove o livro
    public function destroy($id)
    {
        // TODO: verificar se tem empréstimo ativo antes de deletar
    }
}
