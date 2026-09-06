<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Empréstimos — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Meus Empréstimos</span>
            <div class="topbar-actions">
                <a href="/livros" class="btn btn-outline btn-sm">Ver Catálogo</a>
            </div>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <?php if (!empty($leitor['suspenso_ate']) && strtotime($leitor['suspenso_ate']) > time()): ?>
                <div class="flash flash-warning">
                    <span>
                        Sua conta está <strong>suspensa</strong> até
                        <strong><?= date('d/m/Y', strtotime($leitor['suspenso_ate'])) ?></strong>.
                        Não é possível realizar novos empréstimos durante este período.
                    </span>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <span class="card-title">Histórico de Empréstimos</span>
                    <span class="text-muted text-small"><?= count($emprestimos) ?> registro(s)</span>
                </div>
                <div class="table-wrapper">
                    <?php if (empty($emprestimos)): ?>
                        <div class="empty-state">
                            <div class="empty-text">Você não possui empréstimos registrados</div>
                            <div class="empty-sub"><a href="/livros">Explorar o catálogo</a></div>
                        </div>
                    <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Livro</th>
                                <th>Data Empréstimo</th>
                                <th>Prazo</th>
                                <th>Devolução</th>
                                <th>Status</th>
                                <th>Renovações</th>
                                <th style="text-align:right">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($emprestimos as $e): ?>
                            <?php
                                $hoje     = date('Y-m-d');
                                $atrasado = $e['status'] === 'ativo' && $e['data_prevista_devolucao'] < $hoje;
                                $podeRenovar = $e['status'] === 'ativo' && $e['renovacoes'] < 2 && !$atrasado;
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
                                <td>
                                    <div class="cell-main"><?= htmlspecialchars($e['titulo']) ?></div>
                                    <div class="cell-sub"><?= htmlspecialchars($e['autor']) ?></div>
                                </td>
                                <td class="text-small"><?= date('d/m/Y', strtotime($e['data_emprestimo'])) ?></td>
                                <td class="text-small" style="<?= $atrasado ? 'color:var(--danger);font-weight:600;' : '' ?>">
                                    <?= date('d/m/Y', strtotime($e['data_prevista_devolucao'])) ?>
                                </td>
                                <td class="text-small text-muted">
                                    <?= $e['data_devolucao_real'] ? date('d/m/Y', strtotime($e['data_devolucao_real'])) : '—' ?>
                                </td>
                                <td><span class="badge <?= $statusBadge ?>"><?= $statusLabel ?></span></td>
                                <td class="text-small text-center"><?= $e['renovacoes'] ?>/2</td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <?php if ($e['status'] === 'ativo' && $podeRenovar): ?>
                                            <form method="POST" action="/meus-emprestimos/<?= $e['id'] ?>/renovar" class="inline-form">
                                                <button type="submit" class="btn btn-outline btn-xs" id="btn-renovar-<?= $e['id'] ?>">Renovar</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
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
