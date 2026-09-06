<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistema de Biblioteca</title>
    <meta name="description" content="Acesse o Sistema de Gerenciamento de Biblioteca com suas credenciais.">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <header class="auth-topbar">
        <span class="brand-name">Sistema de Biblioteca</span>
        <span class="brand-tagline">Gerenciamento de Acervo</span>
    </header>

    <main class="auth-main">
        <div class="auth-card">
            <h1>Acesso ao Sistema</h1>
            <p class="auth-desc">Informe suas credenciais para acessar o catálogo e realizar empréstimos.</p>

            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <form method="POST" action="/login" id="form-login">
                <div class="form-group">
                    <label for="email" class="form-label">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="admin@biblioteca.com"
                        autocomplete="email"
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="senha" class="form-label">Senha</label>
                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        class="form-control"
                        placeholder="••••••"
                        autocomplete="current-password"
                        required
                    >
                </div>
                <button type="submit" class="btn-primary" id="btn-entrar">Entrar</button>
            </form>
        </div>
    </main>
</body>
</html>
