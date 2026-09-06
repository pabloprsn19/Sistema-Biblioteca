<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Gestão de Usuários</span>
            <div class="topbar-actions">
                <a href="/usuarios/novo" class="btn btn-primary btn-sm" id="btn-novo-usuario">Novo Usuário</a>
            </div>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <div class="card">
                <div class="card-header">
                    <span class="card-title">Usuários Cadastrados</span>
                    <input type="text" id="busca-usuario" class="form-control" placeholder="Buscar por nome ou e-mail..." style="max-width:240px;">
                </div>
                <div class="table-wrapper">
                    <?php if (empty($usuarios)): ?>
                        <div class="empty-state">
                            <div class="empty-text">Nenhum usuário cadastrado</div>
                        </div>
                    <?php else: ?>
                    <table class="table" id="tabela-usuarios">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome / E-mail</th>
                                <th>Perfil</th>
                                <th>Status</th>
                                <th>Suspenso até</th>
                                <th>Cadastro</th>
                                <th style="text-align:right">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <?php
                                $suspenso = !empty($u['suspenso_ate']) && strtotime($u['suspenso_ate']) > time();
                                $perfilBadge = match($u['perfil']) {
                                    'administrador' => 'badge-red',
                                    'atendente'     => 'badge-yellow',
                                    default         => 'badge-blue',
                                };
                                $ehProprio = $u['id'] == ($_SESSION['usuario_id'] ?? 0);
                            ?>
                            <tr>
                                <td class="text-muted text-small"><?= $u['id'] ?></td>
                                <td>
                                    <div class="cell-main"><?= htmlspecialchars($u['nome']) ?> <?= $ehProprio ? '<span class="badge badge-gray" style="font-size:10px;">Você</span>' : '' ?></div>
                                    <div class="cell-sub"><?= htmlspecialchars($u['email']) ?></div>
                                </td>
                                <td><span class="badge <?= $perfilBadge ?>"><?= ucfirst($u['perfil']) ?></span></td>
                                <td>
                                    <?php if ($u['ativo']): ?>
                                        <span class="badge badge-green">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge badge-gray">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-small">
                                    <?php if ($suspenso): ?>
                                        <span style="color:var(--danger); font-weight:600;">
                                            <?= date('d/m/Y', strtotime($u['suspenso_ate'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-small text-muted"><?= $u['criado_em'] ? date('d/m/Y', strtotime($u['criado_em'])) : '—' ?></td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <a href="/usuarios/<?= $u['id'] ?>/editar" class="btn btn-ghost btn-xs" id="btn-editar-usuario-<?= $u['id'] ?>">Editar</a>

                                        <?php if (!$ehProprio): ?>
                                            <?php if ($suspenso): ?>
                                                <form method="POST" action="/usuarios/<?= $u['id'] ?>/reativar" class="inline-form">
                                                    <button type="submit" class="btn btn-outline btn-xs" id="btn-reativar-<?= $u['id'] ?>">Reativar</button>
                                                </form>
                                            <?php elseif ($u['perfil'] === 'leitor'): ?>
                                                <button type="button" class="btn btn-danger btn-xs"
                                                        id="btn-suspender-<?= $u['id'] ?>"
                                                        onclick="abrirSuspensao(<?= $u['id'] ?>, '<?= htmlspecialchars($u['nome']) ?>')">
                                                    Suspender
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <span><?= count($usuarios) ?> usuário(s)</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<div id="modal-suspensao" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:999; align-items:center; justify-content:center;">
    <div style="background:var(--white); border-radius:8px; padding:28px; width:380px; box-shadow:var(--shadow-md);">
        <h3 style="margin-bottom:8px; font-size:15px;">Suspender Usuário</h3>
        <p id="modal-nome-usuario" style="color:var(--gray-500); font-size:13px; margin-bottom:16px;"></p>
        <form method="POST" id="form-suspender">
            <div class="form-group">
                <label class="form-label" for="suspenso_ate">Suspenso até <span class="required">*</span></label>
                <input type="date" id="suspenso_ate" name="suspenso_ate" class="form-control"
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-danger" id="btn-confirmar-suspensao">Confirmar Suspensão</button>
                <button type="button" class="btn btn-outline" onclick="fecharModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirSuspensao(id, nome) {
    const modal = document.getElementById('modal-suspensao');
    document.getElementById('modal-nome-usuario').textContent = 'Usuário: ' + nome;
    document.getElementById('form-suspender').action = '/usuarios/' + id + '/suspender';
    modal.style.display = 'flex';
}
function fecharModal() {
    document.getElementById('modal-suspensao').style.display = 'none';
}
document.getElementById('modal-suspensao').addEventListener('click', function(e) {
    if (e.target === this) fecharModal();
});
document.getElementById('busca-usuario')?.addEventListener('input', function () {
    const t = this.value.toLowerCase();
    document.querySelectorAll('#tabela-usuarios tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(t) ? '' : 'none';
    });
});
</script>
</body>
</html>
