<?php
use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\Usuario;

$perfil = $_SESSION['usuario_perfil'] ?? '';

if (in_array($perfil, ['atendente', 'administrador'])) {
    $modelE = new Emprestimo();
    $modelL = new Livro();
    $totalAtivos    = $modelE->totalAtivos();
    $totalAtrasados = $modelE->totalAtrasados();
    $livros         = $modelL->listarTodos();
    $totalLivros    = count($livros);
    $atrasados      = $modelE->listarAtrasados();

    if ($perfil === 'administrador') {
        $modelU = new Usuario();
        $totalUsuarios = count($modelU->listarTodos());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Início</span>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <?php if (in_array($perfil, ['atendente', 'administrador'])): ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-info">
                            <div class="stat-value"><?= $totalLivros ?></div>
                            <div class="stat-label">Títulos no Acervo</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <div class="stat-value"><?= $totalAtivos ?></div>
                            <div class="stat-label">Empréstimos Ativos</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <div class="stat-value"><?= $totalAtrasados ?></div>
                            <div class="stat-label">Em Atraso</div>
                        </div>
                    </div>
                    <?php if ($perfil === 'administrador' && isset($totalUsuarios)): ?>
                    <div class="stat-card">
                        <div class="stat-info">
                            <div class="stat-value"><?= $totalUsuarios ?></div>
                            <div class="stat-label">Usuários Cadastrados</div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Acesso Rápido</span>
                    </div>
                    <div class="card-body">
                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            <a href="/emprestimos/novo" class="btn btn-primary" id="btn-dash-emprestimo">Registrar Empréstimo</a>
                            <a href="/livros/novo"      class="btn btn-outline"  id="btn-dash-livro">Cadastrar Livro</a>
                            <a href="/emprestimos?status=ativo" class="btn btn-outline" id="btn-dash-ativos">Ver Ativos</a>
                            <a href="/relatorios"       class="btn btn-outline"  id="btn-dash-relatorio">Relatórios</a>
                            <?php if ($perfil === 'administrador'): ?>
                            <a href="/usuarios/novo"   class="btn btn-outline"  id="btn-dash-usuario">Novo Usuário</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($atrasados)): ?>
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Empréstimos em Atraso</span>
                        <a href="/relatorios" class="btn btn-outline btn-sm">Ver todos</a>
                    </div>
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Leitor</th>
                                    <th>Livro</th>
                                    <th>Prazo</th>
                                    <th>Atraso</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach (array_slice($atrasados, 0, 5) as $a): ?>
                                <?php $diasAtraso = (int) round((time() - strtotime($a['data_prevista_devolucao'])) / 86400); ?>
                                <tr>
                                    <td>
                                        <div class="cell-main"><?= htmlspecialchars($a['usuario_nome']) ?></div>
                                        <div class="cell-sub"><?= htmlspecialchars($a['usuario_email']) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($a['titulo']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($a['data_prevista_devolucao'])) ?></td>
                                    <td><span class="badge badge-red"><?= $diasAtraso ?> dia(s)</span></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? '') ?></span>
                    </div>
                    <div class="card-body">
                        <p style="color:var(--gray-600); margin-bottom:16px;">Selecione uma opção para navegar no sistema:</p>
                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            <a href="/livros" class="btn btn-primary" id="btn-leitor-catalogo">Explorar Catálogo</a>
                            <a href="/meus-emprestimos" class="btn btn-outline" id="btn-leitor-meus">Meus Empréstimos</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
</body>
</html>
