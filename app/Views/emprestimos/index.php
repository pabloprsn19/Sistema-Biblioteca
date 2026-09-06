<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimos — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Gestão de Empréstimos</span>
            <div class="topbar-actions">
                <a href="/emprestimos/novo" class="btn btn-primary btn-sm" id="btn-novo-emprestimo-top">Novo Empréstimo</a>
            </div>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <div class="stats-grid" style="grid-template-columns: repeat(auto-fill, minmax(160px,1fr)); margin-bottom:20px;">
                <div class="stat-card">
                    <div class="stat-info">
                        <div class="stat-value"><?= $totalAtivos ?></div>
                        <div class="stat-label">Ativos</div>
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
                    <span class="card-title">Lista de Empréstimos</span>
                    <div class="filters-bar">
                        <form method="GET" action="/emprestimos" style="display:flex; gap:8px; align-items:center;">
                            <select name="status" class="form-control" style="max-width:160px;" onchange="this.form.submit()">
                                <option value="" <?= empty($_GET['status']) ? 'selected':'' ?>>Todos os status</option>
                                <option value="ativo"      <?= ($_GET['status']??'') === 'ativo'      ? 'selected':'' ?>>Ativos</option>
                                <option value="devolvido"  <?= ($_GET['status']??'') === 'devolvido'  ? 'selected':'' ?>>Devolvidos</option>
                            </select>
                        </form>
                        <input type="text" id="busca-emp" class="form-control" placeholder="Buscar leitor ou livro..." style="max-width:220px;">
                    </div>
                </div>
                <div class="table-wrapper">
                    <?php if (empty($emprestimos)): ?>
                        <div class="empty-state">
                            <div class="empty-text">Nenhum empréstimo encontrado</div>
                        </div>
                    <?php else: ?>
                    <table class="table" id="tabela-emprestimos">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Leitor</th>
                                <th>Livro</th>
                                <th>Data Empréstimo</th>
                                <th>Prazo</th>
                                <th>Status</th>
                                <th>Renovações</th>
                                <th style="text-align:right">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($emprestimos as $e): ?>
                            <?php
                                $hoje    = date('Y-m-d');
                                $atrasado = $e['status'] === 'ativo' && $e['data_prevista_devolucao'] < $hoje;
                                $statusBadge = match($e['status']) {
                                    'ativo'     => $atrasado ? 'badge-red' : 'badge-green',
                                    'devolvido' => 'badge-gray',
                                    default     => 'badge-yellow',
                                };
                                $statusLabel = match($e['status']) {
                                    'ativo'     => $atrasado ? 'Em Atraso' : 'Ativo',
                                    'devolvido' => 'Devolvido',
                                    default     => $e['status'],
                                };
                            ?>
                            <tr>
                                <td class="text-muted text-small"><?= $e['id'] ?></td>
                                <td>
                                    <div class="cell-main"><?= htmlspecialchars($e['usuario_nome']) ?></div>
                                    <div class="cell-sub"><?= htmlspecialchars($e['usuario_email']) ?></div>
                                </td>
                                <td>
                                    <div class="cell-main"><?= htmlspecialchars($e['titulo']) ?></div>
                                    <div class="cell-sub"><?= htmlspecialchars($e['autor']) ?></div>
                                </td>
                                <td class="text-small"><?= date('d/m/Y', strtotime($e['data_emprestimo'])) ?></td>
                                <td class="text-small <?= $atrasado ? 'font-semibold' : '' ?>" style="<?= $atrasado ? 'color:var(--danger)' : '' ?>">
                                    <?= date('d/m/Y', strtotime($e['data_prevista_devolucao'])) ?>
                                </td>
                                <td><span class="badge <?= $statusBadge ?>"><?= $statusLabel ?></span></td>
                                <td class="text-small text-center"><?= $e['renovacoes'] ?>/2</td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <a href="/emprestimos/<?= $e['id'] ?>" class="btn btn-ghost btn-xs" id="btn-ver-<?= $e['id'] ?>">Ver</a>
                                        <?php if ($e['status'] === 'ativo'): ?>
                                            <a href="/emprestimos/<?= $e['id'] ?>/devolver" class="btn btn-outline btn-xs" id="btn-devolver-<?= $e['id'] ?>">Devolver</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <span><?= count($emprestimos) ?> registro(s) encontrado(s)</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
document.getElementById('busca-emp')?.addEventListener('input', function () {
    const t = this.value.toLowerCase();
    document.querySelectorAll('#tabela-emprestimos tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(t) ? '' : 'none';
    });
});
</script>
</body>
</html>
