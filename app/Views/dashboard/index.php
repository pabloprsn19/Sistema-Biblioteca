<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – Bibliotech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body>

<!-- sidebar de navegação -->
<nav id="sidebar" class="sidebar">

    <div class="sidebar-topo">
        <div class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
            </svg>
        </div>
        <span class="nome-sistema">Bibliotech</span>
    </div>

    <div class="usuario-sidebar">
        <div class="avatar">
            <?= strtoupper(mb_substr($_SESSION['usuario_nome'] ?? 'U', 0, 1)) ?>
        </div>
        <div class="info-usuario">
            <span class="nome-usuario"><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?></span>
            <span class="perfil-usuario"><?= ucfirst($_SESSION['usuario_perfil'] ?? '') ?></span>
        </div>
    </div>

    <ul class="menu">
        <li class="secao-label">Geral</li>
        <li>
            <a href="/" class="item-menu ativo" id="menu-dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="/livros" class="item-menu" id="menu-livros">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
                <span>Catálogo</span>
            </a>
        </li>
        <li>
            <a href="/emprestimos" class="item-menu" id="menu-emprestimos">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <polyline points="9 11 12 14 22 4"/>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                <span>Empréstimos</span>
            </a>
        </li>

        <?php if (in_array($_SESSION['usuario_perfil'] ?? '', ['atendente', 'administrador'])): ?>
        <li class="secao-label">Gestão</li>
        <li>
            <a href="/usuarios" class="item-menu" id="menu-usuarios">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span>Usuários</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>

    <div class="sidebar-rodape">
        <a href="/logout" class="item-menu item-sair" id="menu-sair">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            <span>Sair</span>
        </a>
    </div>
</nav>

<!-- overlay pra fechar a sidebar no mobile -->
<div class="overlay" id="overlay"></div>

<!-- conteúdo principal -->
<div class="conteudo-principal">

    <!-- topbar -->
    <header class="topbar">
        <button class="btn-toggle-menu" id="btn-menu" aria-label="Abrir menu">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

        <h1 class="titulo-pagina">Dashboard</h1>

        <div class="topbar-direita">
            <span class="data-atual"><?= date('d/m/Y') ?></span>
            <div class="avatar-topo">
                <?= strtoupper(mb_substr($_SESSION['usuario_nome'] ?? 'U', 0, 1)) ?>
            </div>
        </div>
    </header>

    <!-- área de conteúdo -->
    <main class="area-conteudo">

        <!-- saudação -->
        <section class="saudacao">
            <div>
                <h2 id="texto-saudacao">Olá, <?= htmlspecialchars(explode(' ', $_SESSION['usuario_nome'] ?? 'Usuário')[0]) ?>!</h2>
                <p>Resumo geral do sistema hoje.</p>
            </div>
            <a href="/emprestimos/novo" class="btn-primario" id="btn-novo-emprestimo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Novo Empréstimo
            </a>
        </section>

        <!-- cards de resumo -->
        <section class="grid-cards">

            <div class="card card-azul" id="card-livros">
                <div class="card-icone">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>
                <div class="card-info">
                    <span class="card-valor">0</span>
                    <span class="card-label">Livros no Acervo</span>
                </div>
            </div>

            <div class="card card-roxo" id="card-emprestimos">
                <div class="card-icone">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <polyline points="9 11 12 14 22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                </div>
                <div class="card-info">
                    <span class="card-valor">0</span>
                    <span class="card-label">Empréstimos Ativos</span>
                </div>
            </div>

            <div class="card card-vermelho" id="card-atrasados">
                <div class="card-icone">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div class="card-info">
                    <span class="card-valor">0</span>
                    <span class="card-label">Em Atraso</span>
                </div>
            </div>

            <div class="card card-verde" id="card-leitores">
                <div class="card-icone">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="card-info">
                    <span class="card-valor">0</span>
                    <span class="card-label">Leitores Cadastrados</span>
                </div>
            </div>

        </section>

        <!-- tabela de empréstimos recentes -->
        <section class="painel-tabela">
            <div class="painel-cabecalho">
                <h3>Empréstimos Recentes</h3>
                <a href="/emprestimos" id="link-ver-todos">Ver todos</a>
            </div>
            <div class="tabela-wrapper">
                <table class="tabela" id="tabela-emprestimos">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Leitor</th>
                            <th>Livro</th>
                            <th>Data Saída</th>
                            <th>Devolução Prevista</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- os dados virão do controller -->
                        <tr>
                            <td colspan="6">
                                <div class="sem-dados">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.5">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                    <p>Nenhum empréstimo registrado.</p>
                                    <a href="/emprestimos/novo" id="link-primeiro-emprestimo">Registrar agora</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- acesso rápido -->
        <section class="acesso-rapido">
            <h3>Acesso Rápido</h3>
            <div class="grid-rapido">
                <a href="/livros/novo" class="atalho" id="atalho-novo-livro">
                    <div class="atalho-icone icone-azul">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                    </div>
                    <span>Cadastrar Livro</span>
                </a>
                <a href="/emprestimos/novo" class="atalho" id="atalho-emprestimo">
                    <div class="atalho-icone icone-roxo">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <polyline points="9 11 12 14 22 4"/>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                    </div>
                    <span>Novo Empréstimo</span>
                </a>
                <a href="/livros" class="atalho" id="atalho-catalogo">
                    <div class="atalho-icone icone-verde">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </div>
                    <span>Buscar Livro</span>
                </a>
                <a href="/emprestimos" class="atalho" id="atalho-emprestimos">
                    <div class="atalho-icone icone-laranja">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <span>Ver Empréstimos</span>
                </a>
            </div>
        </section>

    </main>
</div>

<script src="/assets/js/dashboard.js" defer></script>
</body>
</html>
