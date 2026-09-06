<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Livro — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Cadastrar Livro</span>
            <div class="topbar-actions">
                <a href="/livros" class="btn btn-outline btn-sm">Voltar ao Catálogo</a>
            </div>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <div class="card" style="max-width:700px;">
                <div class="card-header">
                    <span class="card-title">Novo Livro</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="/livros" id="form-criar-livro">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="titulo">Título <span class="required">*</span></label>
                                <input type="text" id="titulo" name="titulo" class="form-control"
                                       placeholder="Ex: Dom Quixote" required maxlength="255"
                                       value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="autor">Autor <span class="required">*</span></label>
                                <input type="text" id="autor" name="autor" class="form-control"
                                       placeholder="Ex: Miguel de Cervantes" required maxlength="255"
                                       value="<?= htmlspecialchars($_POST['autor'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="isbn">ISBN <span class="required">*</span></label>
                                <input type="text" id="isbn" name="isbn" class="form-control"
                                       placeholder="Ex: 978-85-325-2366-4" required maxlength="20"
                                       value="<?= htmlspecialchars($_POST['isbn'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="editora">Editora</label>
                                <input type="text" id="editora" name="editora" class="form-control"
                                       placeholder="Ex: Editora Record" maxlength="255"
                                       value="<?= htmlspecialchars($_POST['editora'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="ano_publicacao">Ano de Publicação</label>
                                <input type="number" id="ano_publicacao" name="ano_publicacao" class="form-control"
                                       placeholder="Ex: 2008" min="1000" max="<?= date('Y') ?>"
                                       value="<?= htmlspecialchars($_POST['ano_publicacao'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="categoria_id">Categoria</label>
                                <select id="categoria_id" name="categoria_id" class="form-control">
                                    <option value="">— Selecionar —</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"
                                            <?= (($_POST['categoria_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
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
                                   value="<?= htmlspecialchars($_POST['total_exemplares'] ?? '1') ?>">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="btn-salvar-livro">Salvar Livro</button>
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
