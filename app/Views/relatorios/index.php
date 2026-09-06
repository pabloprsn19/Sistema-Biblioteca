<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Relatórios</span>
            <div class="topbar-actions">
                <span class="text-muted text-small">Atualizado em <?= date('d/m/Y \à\s H:i') ?></span>
            </div>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

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
            </div>

            <div class="card">
                <div class="card-header">
                    <span class="card-title">Empréstimos em Atraso (<?= count($atrasados) ?>)</span>
                    <?php if (!empty($atrasados)): ?>
                        <span class="badge badge-red"><?= count($atrasados) ?> pendente(s)</span>
                    <?php endif; ?>
                </div>
                <div class="table-wrapper">
                    <?php if (empty($atrasados)): ?>
                        <div class="empty-state">
                            <div class="empty-text">Nenhum empréstimo em atraso</div>
                        </div>
                    <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Leitor</th>
                                <th>Livro</th>
                                <th>Prazo</th>
                                <th>Dias em Atraso</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($atrasados as $a): ?>
                            <?php $diasAtraso = (int) ceil((time() - strtotime($a['data_prevista_devolucao'])) / 86400); ?>
                            <tr>
                                <td>
                                    <div class="cell-main"><?= htmlspecialchars($a['usuario_nome']) ?></div>
                                    <div class="cell-sub"><?= htmlspecialchars($a['usuario_email']) ?></div>
                                </td>
                                <td>
                                    <div class="cell-main"><?= htmlspecialchars($a['titulo']) ?></div>
                                    <div class="cell-sub"><?= htmlspecialchars($a['autor']) ?></div>
                                </td>
                                <td class="text-small" style="color:var(--danger); font-weight:600;">
                                    <?= date('d/m/Y', strtotime($a['data_prevista_devolucao'])) ?>
                                </td>
                                <td><span class="badge badge-red"><?= $diasAtraso ?> dia(s)</span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span class="card-title">Empréstimos Ativos (<?= count($ativos) ?>)</span>
                    <a href="/emprestimos?status=ativo" class="btn btn-outline btn-sm">Ver todos</a>
                </div>
                <div class="table-wrapper">
                    <?php if (empty($ativos)): ?>
                        <div class="empty-state">
                            <div class="empty-text">Nenhum empréstimo ativo</div>
                        </div>
                    <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Leitor</th>
                                <th>Livro</th>
                                <th>Data Empréstimo</th>
                                <th>Prazo</th>
                                <th>Renovações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach (array_slice($ativos, 0, 20) as $a): ?>
                            <tr>
                                <td>
                                    <div class="cell-main"><?= htmlspecialchars($a['usuario_nome']) ?></div>
                                    <div class="cell-sub"><?= htmlspecialchars($a['usuario_email']) ?></div>
                                </td>
                                <td><?= htmlspecialchars($a['titulo']) ?></td>
                                <td class="text-small"><?= date('d/m/Y', strtotime($a['data_emprestimo'])) ?></td>
                                <td class="text-small"><?= date('d/m/Y', strtotime($a['data_prevista_devolucao'])) ?></td>
                                <td class="text-small text-center"><?= $a['renovacoes'] ?>/2</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (count($ativos) > 20): ?>
                        <div class="pagination">
                            <span>Exibindo 20 de <?= count($ativos) ?> registros. <a href="/emprestimos?status=ativo">Ver todos</a></span>
                        </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span class="card-title">Livros Mais Emprestados</span>
                </div>
                <div class="table-wrapper">
                    <?php if (empty($maisEmprestados)): ?>
                        <div class="empty-state">
                            <div class="empty-text">Sem dados de empréstimos ainda</div>
                        </div>
                    <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th style="text-align:center">Total de Empréstimos</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($maisEmprestados as $i => $m): ?>
                            <tr>
                                <td class="text-muted"><?= ($i + 1) ?></td>
                                <td class="cell-main"><?= htmlspecialchars($m['titulo']) ?></td>
                                <td class="cell-sub"><?= htmlspecialchars($m['autor']) ?></td>
                                <td style="text-align:center">
                                    <span class="badge badge-blue"><?= $m['total_emprestimos'] ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
