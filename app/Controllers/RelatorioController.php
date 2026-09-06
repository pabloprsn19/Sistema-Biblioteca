<?php

namespace App\Controllers;

use App\Models\Emprestimo;
use App\Models\Livro;

class RelatorioController
{
    public function index()
    {
        $modelEmprestimo = new Emprestimo();
        $modelLivro      = new Livro();

        $atrasados      = $modelEmprestimo->listarAtrasados();
        $ativos         = $modelEmprestimo->listarTodos('ativo');
        $maisEmprestados = $modelEmprestimo->maisEmprestados(10);
        $totalLivros    = count($modelLivro->listarTodos());
        $totalAtivos    = $modelEmprestimo->totalAtivos();
        $totalAtrasados = $modelEmprestimo->totalAtrasados();

        require __DIR__ . '/../Views/relatorios/index.php';
    }
}
