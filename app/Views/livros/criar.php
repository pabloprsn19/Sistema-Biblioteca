<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Livro – Bibliotech</title>
</head>
<body>

<h1>Cadastrar Livro</h1>
<a href="/livros">← Voltar à listagem</a>

<hr>

<?php if (!empty($_SESSION['erro'])): ?>
    <p style="color:red;"><?= htmlspecialchars($_SESSION['erro']) ?></p>
    <?php unset($_SESSION['erro']); ?>
<?php endif; ?>

<form method="POST" action="/livros">

    <p>
        <label>Título *<br>
            <input type="text" name="titulo" required value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>">
        </label>
    </p>

    <p>
        <label>Autor *<br>
            <input type="text" name="autor" required value="<?= htmlspecialchars($_POST['autor'] ?? '') ?>">
        </label>
    </p>

    <p>
        <label>ISBN *<br>
            <input type="text" name="isbn" required value="<?= htmlspecialchars($_POST['isbn'] ?? '') ?>">
        </label>
    </p>

    <p>
        <label>Editora<br>
            <input type="text" name="editora" value="<?= htmlspecialchars($_POST['editora'] ?? '') ?>">
        </label>
    </p>

    <p>
        <label>Ano de Publicação<br>
            <input type="number" name="ano_publicacao" min="1000" max="<?= date('Y') ?>"
                   value="<?= htmlspecialchars($_POST['ano_publicacao'] ?? '') ?>">
        </label>
    </p>

    <p>
        <label>Categoria<br>
            <select name="categoria_id">
                <option value="">-- Selecione --</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>"
                        <?= (($_POST['categoria_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>

    <p>
        <label>Total de Exemplares<br>
            <input type="number" name="total_exemplares" min="1"
                   value="<?= htmlspecialchars($_POST['total_exemplares'] ?? '1') ?>">
        </label>
    </p>

    <p>
        <button type="submit">Salvar Livro</button>
        <a href="/livros">Cancelar</a>
    </p>

</form>

</body>
</html>
