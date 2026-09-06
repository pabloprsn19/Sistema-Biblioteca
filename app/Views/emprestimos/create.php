<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Empréstimo — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Registrar Empréstimo</span>
            <div class="topbar-actions">
                <a href="/emprestimos" class="btn btn-outline btn-sm">Voltar</a>
            </div>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <div class="card" style="max-width:640px;">
                <div class="card-header">
                    <span class="card-title">Novo Empréstimo</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="/emprestimos" id="form-novo-emprestimo">
                        <div class="form-group">
                            <label class="form-label" for="usuario_id">Leitor <span class="required">*</span></label>
                            <select id="usuario_id" name="usuario_id" class="form-control" required>
                                <option value="">— Selecionar leitor —</option>
                                <?php foreach ($leitores as $leitor): ?>
                                    <?php
                                        $suspenso = !empty($leitor['suspenso_ate']) && strtotime($leitor['suspenso_ate']) > time();
                                        $label = htmlspecialchars($leitor['nome'] . ' (' . $leitor['email'] . ')');
                                        if ($suspenso) $label .= ' — SUSPENSO';
                                    ?>
                                    <option value="<?= $leitor['id'] ?>"
                                            <?= $suspenso ? 'disabled style="color:var(--danger)"' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-hint">Leitores marcados como SUSPENSO não podem pegar livros.</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="livro_id">Livro <span class="required">*</span></label>
                            <select id="livro_id" name="livro_id" class="form-control" required>
                                <option value="">— Selecionar livro —</option>
                                <?php foreach ($livros as $livro): ?>
                                    <?php $disponivel = ((int)$livro['disponiveis']) > 0; ?>
                                    <option value="<?= $livro['id'] ?>"
                                            <?= !$disponivel ? 'disabled style="color:var(--gray-400)"' : '' ?>>
                                        <?= htmlspecialchars($livro['titulo'] . ' — ' . $livro['autor']) ?>
                                        (<?= $livro['disponiveis'] ?> disponível<?= $livro['disponiveis'] != 1 ? 'is' : '' ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-hint" style="margin-bottom:16px;">
                            O prazo padrão de devolução é de <strong>14 dias</strong> a partir de hoje.
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="btn-registrar-emprestimo">Registrar Empréstimo</button>
                            <a href="/emprestimos" class="btn btn-outline">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
