<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Livros – Bibliotech</title>
</head>
<body>

<h1>Catálogo de Livros</h1>
<a href="/livros/novo">+ Cadastrar novo livro</a>

<hr>

<?php if (!empty($_SESSION['sucesso'])): ?>
    <p style="color:green;"><?= htmlspecialchars($_SESSION['sucesso']) ?></p>
    <?php unset($_SESSION['sucesso']); ?>
<?php endif; ?>

<?php if (empty($livros)): ?>
    <p>Nenhum livro cadastrado ainda.</p>
<?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Título</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Editora</th>
                <th>Ano</th>
                <th>Categoria</th>
                <th>Exemplares</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($livros as $livro): ?>
                <tr>
                    <td><?= $livro['id'] ?></td>
                    <td><?= htmlspecialchars($livro['titulo']) ?></td>
                    <td><?= htmlspecialchars($livro['autor']) ?></td>
                    <td><?= htmlspecialchars($livro['isbn']) ?></td>
                    <td><?= htmlspecialchars($livro['editora'] ?: '—') ?></td>
                    <td><?= $livro['ano_publicacao'] ?: '—' ?></td>
                    <td><?= htmlspecialchars($livro['categoria']) ?></td>
                    <td><?= $livro['total_exemplares'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><?= count($livros) ?> livro(s) encontrado(s).</p>
<?php endif; ?>

</body>
</html>
