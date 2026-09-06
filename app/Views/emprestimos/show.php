<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Empréstimo — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Detalhes do Empréstimo #<?= $emprestimo['id'] ?></span>
            <div class="topbar-actions">
                <a href="/emprestimos" class="btn btn-outline btn-sm">Voltar</a>
            </div>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <?php
                $hoje     = date('Y-m-d');
                $atrasado = $emprestimo['status'] === 'ativo' && $emprestimo['data_prevista_devolucao'] < $hoje;
            ?>

            <div class="card" style="max-width:640px;">
                <div class="card-header">
                    <span class="card-title">Empréstimo #<?= $emprestimo['id'] ?></span>
                    <?php if ($emprestimo['status'] === 'ativo'): ?>
                        <span class="badge <?= $atrasado ? 'badge-red' : 'badge-green' ?>">
                            <?= $atrasado ? 'Em Atraso' : 'Ativo' ?>
                        </span>
                    <?php else: ?>
                        <span class="badge badge-gray">Devolvido</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <table class="table" style="margin-bottom:0;">
                        <tbody>
                            <tr>
                                <td class="text-muted" style="width:40%">Leitor</td>
                                <td>
                                    <strong><?= htmlspecialchars($emprestimo['usuario_nome']) ?></strong><br>
                                    <span class="text-small text-muted"><?= htmlspecialchars($emprestimo['usuario_email']) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Livro</td>
                                <td>
                                    <strong><?= htmlspecialchars($emprestimo['titulo']) ?></strong><br>
                                    <span class="text-small text-muted"><?= htmlspecialchars($emprestimo['autor']) ?></span><br>
                                    <span class="text-small text-muted">ISBN: <?= htmlspecialchars($emprestimo['isbn']) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Data do Empréstimo</td>
                                <td><?= date('d/m/Y', strtotime($emprestimo['data_emprestimo'])) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Prazo de Devolução</td>
                                <td style="<?= $atrasado ? 'color:var(--danger); font-weight:600;' : '' ?>">
                                    <?= date('d/m/Y', strtotime($emprestimo['data_prevista_devolucao'])) ?>
                                    <?php if ($atrasado): ?>
                                        <?php $diasAtraso = (int) round((time() - strtotime($emprestimo['data_prevista_devolucao'])) / 86400); ?>
                                        <span class="badge badge-red"><?= $diasAtraso ?> dia(s) em atraso</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if ($emprestimo['data_devolucao_real']): ?>
                            <tr>
                                <td class="text-muted">Data da Devolução</td>
                                <td><?= date('d/m/Y', strtotime($emprestimo['data_devolucao_real'])) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="text-muted">Renovações</td>
                                <td><?= $emprestimo['renovacoes'] ?>/2</td>
                            </tr>
                            <?php if (!empty($emprestimo['atendente_nome'])): ?>
                            <tr>
                                <td class="text-muted">Registrado por</td>
                                <td><?= htmlspecialchars($emprestimo['atendente_nome']) ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if ($emprestimo['status'] === 'ativo'): ?>
                    <div class="form-actions" style="margin-top:20px; padding-top:16px; border-top:1px solid var(--gray-200);">
                        <a href="/emprestimos/<?= $emprestimo['id'] ?>/devolver" class="btn btn-success" id="btn-devolver-show">Registrar Devolução</a>
                        <a href="/emprestimos" class="btn btn-outline">Voltar</a>
                    </div>
                    <?php else: ?>
                    <div class="form-actions" style="margin-top:20px;">
                        <a href="/emprestimos" class="btn btn-outline">Voltar</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
