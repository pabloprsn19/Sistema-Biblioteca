<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Sistema de Biblioteca</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/login.css">
</head>
<body>

<div class="bg-animado" aria-hidden="true">
    <span></span><span></span><span></span><span></span>
</div>

<main class="container-login">

    <!-- lado esquerdo: informações do sistema -->
    <aside class="painel-marca">
        <div class="conteudo-marca">
            <div class="icone-marca">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    <line x1="12" y1="6" x2="16" y2="6"/>
                    <line x1="12" y1="10" x2="16" y2="10"/>
                </svg>
            </div>
            <h1>BiblioSys</h1>
            <p>Sistema de Gerenciamento de Biblioteca</p>
            <ul>
                <li><span class="dot"></span> Catálogo digital de livros</li>
                <li><span class="dot"></span> Controle de empréstimos</li>
                <li><span class="dot"></span> Três perfis de acesso</li>
            </ul>
        </div>
    </aside>

    <!-- lado direito: formulário -->
    <section class="painel-form">
        <div class="card-login">

            <div class="cabecalho">
                <h2>Acesso ao Sistema</h2>
                <p>Informe suas credenciais para continuar</p>
            </div>

            <?php if (!empty($_SESSION['erro'])): ?>
                <div class="alerta alerta-erro">
                    <?= htmlspecialchars($_SESSION['erro']) ?>
                    <?php unset($_SESSION['erro']); ?>
                </div>
            <?php endif; ?>

            <form id="form-login" method="POST" action="/login">

                <div class="campo">
                    <label for="email">E-mail</label>
                    <div class="input-icone">
                        <span class="icone">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="seu@email.com"
                            autocomplete="email"
                            required
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        >
                    </div>
                </div>

                <div class="campo">
                    <label for="senha">
                        Senha
                        <a href="#" class="link-esqueceu" id="link-esqueceu-senha">Esqueceu?</a>
                    </label>
                    <div class="input-icone">
                        <span class="icone">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            id="senha"
                            name="senha"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" id="btn-ver-senha" class="btn-olho" aria-label="Mostrar senha">
                            <svg class="olho-aberto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="olho-fechado escondido" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="lembrar">
                    <input type="checkbox" id="lembrar" name="lembrar" value="1">
                    <label for="lembrar">Manter conectado</label>
                </div>

                <button type="submit" id="btn-entrar" class="btn-entrar">
                    <span class="texto-btn">Entrar</span>
                    <span class="carregando escondido">aguarde...</span>
                </button>

            </form>
        </div>

        <p class="rodape-login">© <?= date('Y') ?> BiblioSys</p>
    </section>

</main>

<script src="/assets/js/login.js" defer></script>
</body>
</html>
