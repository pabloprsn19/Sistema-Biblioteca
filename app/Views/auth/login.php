<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login – Sistema Biblioteca</title>
</head>
<body>

<h1>Sistema de Gerenciamento de Biblioteca</h1>
<h2>Login</h2>

<?php if (!empty($_SESSION['erro'])): ?>
    <p style="color:red;"><?= htmlspecialchars($_SESSION['erro']) ?></p>
    <?php unset($_SESSION['erro']); ?>
<?php endif; ?>

<form method="POST" action="/login">
    <p>
        <label>E-mail:<br>
            <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </label>
    </p>
    <p>
        <label>Senha:<br>
            <input type="password" name="senha" required>
        </label>
    </p>
    <p>
        <button type="submit">Entrar</button>
    </p>
</form>

</body>
</html>
