<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Livro — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Editar Livro</span>
            <div class="topbar-actions">
                <a href="/livros" class="btn btn-outline btn-sm">Voltar ao Catálogo</a>
            </div>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <div class="card" style="max-width:700px;">
                <div class="card-header">
                    <span class="card-title">Editando: <?= htmlspecialchars($livro['titulo']) ?></span>
                </div>
                <div class="card-body">
                    <form method="POST" action="/livros/<?= $livro['id'] ?>/editar" id="form-editar-livro">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="titulo">Título <span class="required">*</span></label>
                                <input type="text" id="titulo" name="titulo" class="form-control"
                                       required maxlength="255"
                                       value="<?= htmlspecialchars($livro['titulo']) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="autor">Autor <span class="required">*</span></label>
                                <input type="text" id="autor" name="autor" class="form-control"
                                       required maxlength="255"
                                       value="<?= htmlspecialchars($livro['autor']) ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="isbn">ISBN <span class="required">*</span></label>
                                <input type="text" id="isbn" name="isbn" class="form-control"
                                       required maxlength="20"
                                       value="<?= htmlspecialchars($livro['isbn']) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="editora">Editora</label>
                                <input type="text" id="editora" name="editora" class="form-control"
                                       maxlength="255"
                                       value="<?= htmlspecialchars($livro['editora'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="ano_publicacao">Ano de Publicação</label>
                                <input type="number" id="ano_publicacao" name="ano_publicacao" class="form-control"
                                       min="1000" max="<?= date('Y') ?>"
                                       value="<?= htmlspecialchars($livro['ano_publicacao'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="categoria_id">Categoria</label>
                                <select id="categoria_id" name="categoria_id" class="form-control">
                                    <option value="">— Selecionar —</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"
                                            <?= ($livro['categoria_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="max-width:180px;">
                            <label class="form-label" for="total_exemplares">Nº de Exemplares <span class="required">*</span></label>
                            <input type="number" id="total_exemplares" name="total_exemplares" class="form-control"
                                   min="1" max="999" required
                                   value="<?= htmlspecialchars($livro['total_exemplares']) ?>">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="btn-atualizar-livro">Salvar Alterações</button>
                            <a href="/livros" class="btn btn-outline">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
