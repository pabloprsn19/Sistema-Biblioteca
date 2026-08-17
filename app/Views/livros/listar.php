<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Livros – Sistema Biblioteca</title>
</head>
<body>

<h1>Sistema de Gerenciamento de Biblioteca</h1>

<nav>
    <a href="/">Página Inicial</a> |
    <a href="/livros">Catálogo de Livros</a> |
    <?php if (($_SESSION['usuario_perfil'] ?? '') === 'leitor'): ?>
        <a href="/meus-emprestimos">Meus Empréstimos</a> |
    <?php else: ?>
        <a href="/emprestimos">Empréstimos</a> |
    <?php endif; ?>
    <a href="/logout">Sair</a>
</nav>

<hr>

<h2>Catálogo de Livros</h2>
<?php if (isset($_SESSION['usuario_perfil']) && $_SESSION['usuario_perfil'] !== 'leitor'): ?>
    <a href="/livros/novo">+ Cadastrar novo livro</a>
<?php endif; ?>

<hr>

<?php if (!empty($_SESSION['sucesso'])): ?>
    <p style="color:green;"><?= htmlspecialchars($_SESSION['sucesso']) ?></p>
    <?php unset($_SESSION['sucesso']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['erro'])): ?>
    <p style="color:red;"><?= htmlspecialchars($_SESSION['erro']) ?></p>
    <?php unset($_SESSION['erro']); ?>
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
                <th>Disponíveis</th>
                <th>Ações</th>
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
                    <td><?= $livro['disponiveis'] ?></td>
                    <td>
                        <?php if (($_SESSION['usuario_perfil'] ?? '') === 'leitor'): ?>
                            <?php if (in_array($livro['id'], $meusEmprestimosAtivos ?? [])): ?>
                                <form method="POST" action="/livros/<?= $livro['id'] ?>/devolver" style="display:inline;">
                                    <button type="submit" style="background-color: #f44336; color: white; border: none; padding: 5px 10px; cursor: pointer;">Devolver</button>
                                </form>
                            <?php elseif ($livro['disponiveis'] > 0): ?>
                                <form method="POST" action="/livros/<?= $livro['id'] ?>/emprestar" style="display:inline;">
                                    <button type="submit" style="background-color: #4CAF50; color: white; border: none; padding: 5px 10px; cursor: pointer;">Pegar Emprestado</button>
                                </form>
                            <?php else: ?>
                                <button disabled style="background-color: #ccc; border: none; padding: 5px 10px;">Indisponível</button>
                            <?php endif; ?>
                        <?php elseif (isset($_SESSION['usuario_perfil'])): ?>
                            <a href="/livros/<?= $livro['id'] ?>/editar">Editar</a>
                            &nbsp;|
                            <form method="POST" action="/livros/<?= $livro['id'] ?>/excluir"
                                  style="display:inline;"
                                  onsubmit="return confirm('Tem certeza que deseja excluir este livro?')">
                                <button type="submit">Excluir</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><?= count($livros) ?> livro(s) encontrado(s).</p>
<?php endif; ?>

</body>
</html>
