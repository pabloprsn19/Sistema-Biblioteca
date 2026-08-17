<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema Biblioteca</title>
</head>
<body>

<h1>Sistema de Gerenciamento de Biblioteca</h1>

<p>
    Bem-vindo, <strong><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?></strong>
    (<?= htmlspecialchars($_SESSION['usuario_perfil'] ?? '') ?>)
    — <a href="/logout">Sair</a>
</p>

<hr>

<nav>
    <a href="/">Página Inicial</a> |
    <a href="/livros">Catálogo de Livros</a> |
    <?php if (($_SESSION['usuario_perfil'] ?? '') === 'leitor'): ?>
        <a href="/meus-emprestimos">Meus Empréstimos</a>
    <?php else: ?>
        <a href="/emprestimos">Empréstimos</a>
        <?php if (in_array($_SESSION['usuario_perfil'] ?? '', ['atendente', 'administrador'])): ?>
            | <a href="/usuarios">Usuários</a>
        <?php endif; ?>
    <?php endif; ?>
</nav>

<hr>

<h2>Painel</h2>
<p>Data: <?= date('d/m/Y') ?></p>

<ul>
    <li><a href="/livros">Ver catálogo de livros</a></li>
    <?php if (($_SESSION['usuario_perfil'] ?? '') === 'leitor'): ?>
        <li><a href="/meus-emprestimos">Ver meu histórico de empréstimos</a></li>
    <?php else: ?>
        <li><a href="/livros/novo">Cadastrar novo livro</a></li>
        <li><a href="/emprestimos/novo">Registrar empréstimo</a></li>
        <li><a href="/emprestimos">Ver empréstimos gerais</a></li>
    <?php endif; ?>
</ul>

</body>
</html>
