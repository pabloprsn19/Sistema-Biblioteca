<?php
$perfil = $_SESSION['usuario_perfil'] ?? '';
$nome   = $_SESSION['usuario_nome']  ?? 'Usuário';
$uri    = '/' . trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($uri === '') $uri = '/';

function navActive(string $prefix, string $uri): string {
    return str_starts_with($uri, $prefix) ? 'active' : '';
}

$inicial = mb_strtoupper(mb_substr($nome, 0, 1));
$perfilLabel = match($perfil) {
    'administrador' => 'Administrador',
    'atendente'     => 'Atendente',
    default         => 'Leitor',
};
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div>
            <div class="brand-text">Biblioteca</div>
            <div class="brand-sub">Sistema de Gestão</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <span class="nav-section-label">Navegação</span>
        <div class="nav-item">
            <a href="/" class="nav-link <?= $uri === '/' ? 'active' : '' ?>">Início</a>
        </div>
        <div class="nav-item">
            <a href="/livros" class="nav-link <?= navActive('/livros', $uri) ?>">Catálogo</a>
        </div>

        <?php if (in_array($perfil, ['atendente', 'administrador'])): ?>
        <span class="nav-section-label">Circulação</span>
        <div class="nav-item">
            <a href="/emprestimos" class="nav-link <?= navActive('/emprestimos', $uri) ?>">Empréstimos</a>
        </div>
        <div class="nav-item">
            <a href="/relatorios" class="nav-link <?= navActive('/relatorios', $uri) ?>">Relatórios</a>
        </div>
        <?php endif; ?>

        <?php if ($perfil === 'leitor'): ?>
        <span class="nav-section-label">Minha Conta</span>
        <div class="nav-item">
            <a href="/meus-emprestimos" class="nav-link <?= navActive('/meus-emprestimos', $uri) ?>">Meus Empréstimos</a>
        </div>
        <?php endif; ?>

        <?php if ($perfil === 'administrador'): ?>
        <span class="nav-section-label">Administração</span>
        <div class="nav-item">
            <a href="/usuarios" class="nav-link <?= navActive('/usuarios', $uri) ?>">Usuários</a>
        </div>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="avatar"><?= htmlspecialchars($inicial) ?></div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($nome) ?></div>
                <div class="user-role"><?= $perfilLabel ?></div>
            </div>
            <a href="/logout" class="logout-btn" title="Sair da conta">Sair</a>
        </div>
    </div>
</aside>
