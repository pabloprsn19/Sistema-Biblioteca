<?php

namespace App\Controllers;

// tudo que envolve empréstimos e devoluções fica aqui

class EmprestimoController
{
    // lista todos os empréstimos (atendente/admin)
    public function index()
    {
        // TODO: filtrar por status (ativo, devolvido, atrasado)
        require __DIR__ . '/../Views/emprestimos/index.php';
    }

    // lista os empréstimos do leitor logado
    public function meus()
    {
        // TODO: pegar o id do usuário da sessão e filtrar
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

    // salva o empréstimo
    public function store()
    {
        // TODO: checar se o livro tá disponível
        // TODO: calcular a data de devolução
        // TODO: atualizar quantidade disponível do livro
    }

    // registra a devolução
    public function devolver($id)
    {
        // TODO: calcular multa se tiver atrasado
        // TODO: marcar como devolvido e devolver o livro ao estoque
    }

    // renova o prazo
    public function renovar($id)
    {
        // TODO: checar se pode renovar (sem atraso e dentro do limite)
    }
}
