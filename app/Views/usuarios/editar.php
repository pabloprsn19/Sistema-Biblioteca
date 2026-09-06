<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário — Sistema de Biblioteca</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="app-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">Editar Usuário</span>
            <div class="topbar-actions">
                <a href="/usuarios" class="btn btn-outline btn-sm">Voltar</a>
            </div>
        </header>

        <main class="page-content">
            <?php include __DIR__ . '/../partials/flash.php'; ?>

            <div class="card" style="max-width:560px;">
                <div class="card-header">
                    <span class="card-title">Editando: <?= htmlspecialchars($usuario['nome']) ?></span>
                </div>
                <div class="card-body">
                    <form method="POST" action="/usuarios/<?= $usuario['id'] ?>/editar" id="form-editar-usuario">
                        <div class="form-group">
                            <label class="form-label" for="nome">Nome Completo <span class="required">*</span></label>
                            <input type="text" id="nome" name="nome" class="form-control"
                                   required maxlength="255"
                                   value="<?= htmlspecialchars($usuario['nome']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">E-mail <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-control"
                                   required maxlength="191"
                                   value="<?= htmlspecialchars($usuario['email']) ?>">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="senha">Nova Senha</label>
                                <input type="password" id="senha" name="senha" class="form-control"
                                       placeholder="Deixe em branco para manter" minlength="6">
                                <div class="form-hint">Preencha apenas para alterar a senha.</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="perfil">Perfil <span class="required">*</span></label>
                                <select id="perfil" name="perfil" class="form-control" required>
                                    <option value="leitor"        <?= $usuario['perfil'] === 'leitor'        ? 'selected':'' ?>>Leitor</option>
                                    <option value="atendente"     <?= $usuario['perfil'] === 'atendente'     ? 'selected':'' ?>>Atendente</option>
                                    <option value="administrador" <?= $usuario['perfil'] === 'administrador' ? 'selected':'' ?>>Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="btn-atualizar-usuario">Salvar Alterações</button>
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
