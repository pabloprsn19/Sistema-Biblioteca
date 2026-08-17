<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Livro – Sistema Biblioteca</title>
</head>
<body>

<h1>Sistema de Gerenciamento de Biblioteca</h1>

<nav>
    <a href="/">Página Inicial</a> |
    <a href="/livros">Catálogo de Livros</a> |
    <a href="/emprestimos">Empréstimos</a> |
    <a href="/logout">Sair</a>
</nav>

<hr>

<h2>Editar Livro</h2>
<a href="/livros">← Voltar à listagem</a>

<hr>

<?php if (!empty($_SESSION['erro'])): ?>
    <p style="color:red;"><?= htmlspecialchars($_SESSION['erro']) ?></p>
    <?php unset($_SESSION['erro']); ?>
<?php endif; ?>

<form method="POST" action="/livros/<?= $livro['id'] ?>/editar">

    <p>
        <label>Título *<br>
            <input type="text" name="titulo" required value="<?= htmlspecialchars($livro['titulo']) ?>">
        </label>
    </p>

    <p>
        <label>Autor *<br>
            <input type="text" name="autor" required value="<?= htmlspecialchars($livro['autor']) ?>">
        </label>
    </p>

    <p>
        <label>ISBN *<br>
            <input type="text" name="isbn" required value="<?= htmlspecialchars($livro['isbn']) ?>">
        </label>
    </p>

    <p>
        <label>Editora<br>
            <input type="text" name="editora" value="<?= htmlspecialchars($livro['editora'] ?? '') ?>">
        </label>
    </p>

    <p>
        <label>Ano de Publicação<br>
            <input type="number" name="ano_publicacao" min="1000" max="<?= date('Y') ?>"
                   value="<?= htmlspecialchars($livro['ano_publicacao'] ?? '') ?>">
        </label>
    </p>

    <p>
        <label>Categoria<br>
            <select name="categoria_id">
                <option value="">-- Selecione --</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>"
                        <?= ($livro['categoria_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>

    <p>
        <label>Total de Exemplares<br>
            <input type="number" name="total_exemplares" min="1"
                   value="<?= htmlspecialchars($livro['total_exemplares'] ?? '1') ?>">
        </label>
    </p>

    <p>
        <button type="submit">Salvar Alterações</button>
        <a href="/livros">Cancelar</a>
    </p>

</form>

</body>
</html>
