<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Livros — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Catálogo de Livros</span>
            <div class="topbar-actions">
                <?php if (in_array($_SESSION['usuario_perfil'] ?? '', ['atendente', 'administrador'])): ?>
                    <a href="/livros/novo" class="btn btn-primary btn-sm" id="btn-novo-livro">Cadastrar Livro</a>
                <?php endif; ?>
            </div>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <div class="card">
                <div class="card-header">
                    <span class="card-title">Acervo (<?= count($livros) ?> títulos)</span>
                    <div class="filters-bar">
                        <input type="text" id="busca-livro" class="form-control" placeholder="Filtrar por título ou autor..." style="max-width:240px;">
                    </div>
                </div>
                <div class="table-wrapper">
                    <?php if (empty($livros)): ?>
                        <div class="empty-state">
                            <div class="empty-text">Nenhum livro cadastrado</div>
                            <?php if (in_array($_SESSION['usuario_perfil'] ?? '', ['atendente', 'administrador'])): ?>
                                <div class="empty-sub"><a href="/livros/novo">Cadastrar o primeiro livro</a></div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                    <table class="table" id="tabela-livros">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Título / Autor</th>
                                <th>ISBN</th>
                                <th>Editora</th>
                                <th>Ano</th>
                                <th>Categoria</th>
                                <th style="text-align:center">Exemplares</th>
                                <th style="text-align:center">Disponíveis</th>
                                <th style="text-align:right">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($livros as $livro): ?>
                            <tr>
                                <td class="text-muted text-small"><?= $livro['id'] ?></td>
                                <td>
                                    <div class="cell-main"><?= htmlspecialchars($livro['titulo']) ?></div>
                                    <div class="cell-sub"><?= htmlspecialchars($livro['autor']) ?></div>
                                </td>
                                <td class="text-small text-muted"><?= htmlspecialchars($livro['isbn']) ?></td>
                                <td class="text-small"><?= htmlspecialchars($livro['editora'] ?: '—') ?></td>
                                <td class="text-small"><?= $livro['ano_publicacao'] ?: '—' ?></td>
                                <td><span class="badge badge-blue"><?= htmlspecialchars($livro['categoria']) ?></span></td>
                                <td style="text-align:center"><?= $livro['total_exemplares'] ?></td>
                                <td style="text-align:center">
                                    <?php $disp = (int)$livro['disponiveis']; ?>
                                    <span class="badge <?= $disp > 0 ? 'badge-green' : 'badge-red' ?>">
                                        <?= $disp ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                    <?php if (($_SESSION['usuario_perfil'] ?? '') === 'leitor'): ?>
                                        <?php if (in_array($livro['id'], $meusEmprestimosAtivos ?? [])): ?>
                                            <a href="/livros/<?= $livro['id'] ?>/devolver" class="btn btn-outline btn-sm" id="btn-devolver-<?= $livro['id'] ?>">Devolver</a>
                                        <?php elseif ($disp > 0): ?>
                                            <a href="/livros/<?= $livro['id'] ?>/emprestar" class="btn btn-success btn-sm" id="btn-emprestar-<?= $livro['id'] ?>">Solicitar Empréstimo</a>
                                        <?php else: ?>
                                            <span class="badge badge-gray">Indisponível</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="/livros/<?= $livro['id'] ?>/editar" class="btn btn-outline btn-sm" id="btn-editar-livro-<?= $livro['id'] ?>">Editar</a>
                                        <a href="/livros/<?= $livro['id'] ?>/excluir" class="btn btn-danger btn-sm" id="btn-excluir-livro-<?= $livro['id'] ?>">Excluir</a>
                                    <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <span><?= count($livros) ?> título(s) no acervo</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
document.getElementById('busca-livro')?.addEventListener('input', function () {
    const termo = this.value.toLowerCase();
    document.querySelectorAll('#tabela-livros tbody tr').forEach(tr => {
        const texto = tr.textContent.toLowerCase();
        tr.style.display = texto.includes(termo) ? '' : 'none';
    });
});
</script>
</body>
</html>
