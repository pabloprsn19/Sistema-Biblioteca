<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Usuário — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Cadastrar Usuário</span>
            <div class="topbar-actions">
                <a href="/usuarios" class="btn btn-outline btn-sm">Voltar</a>
            </div>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <div class="card" style="max-width:560px;">
                <div class="card-header">
                    <span class="card-title">Novo Usuário</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="/usuarios" id="form-criar-usuario">
                        <div class="form-group">
                            <label class="form-label" for="nome">Nome Completo <span class="required">*</span></label>
                            <input type="text" id="nome" name="nome" class="form-control"
                                   placeholder="Ex: Maria Silva" required maxlength="255"
                                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">E-mail <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-control"
                                   placeholder="Ex: maria@email.com" required maxlength="191"
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="senha">Senha <span class="required">*</span></label>
                                <input type="password" id="senha" name="senha" class="form-control"
                                       placeholder="Mínimo 6 caracteres" required minlength="6">
                                <div class="form-hint">Mínimo de 6 caracteres.</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="perfil">Perfil <span class="required">*</span></label>
                                <select id="perfil" name="perfil" class="form-control" required>
                                    <option value="">— Selecionar —</option>
                                    <option value="leitor"       <?= ($_POST['perfil']??'') === 'leitor'        ? 'selected':'' ?>>Leitor</option>
                                    <option value="atendente"    <?= ($_POST['perfil']??'') === 'atendente'     ? 'selected':'' ?>>Atendente</option>
                                    <option value="administrador" <?= ($_POST['perfil']??'') === 'administrador' ? 'selected':'' ?>>Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="btn-salvar-usuario">Cadastrar</button>
                            <a href="/usuarios" class="btn btn-outline">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
