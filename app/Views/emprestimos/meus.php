<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus Empréstimos – Sistema Biblioteca</title>
</head>
<body>

<h1>Sistema de Gerenciamento de Biblioteca</h1>

<nav>
    <a href="/">Página Inicial</a> |
    <a href="/livros">Catálogo de Livros</a> |
    <a href="/meus-emprestimos">Meus Empréstimos</a> |
    <a href="/logout">Sair</a>
</nav>

<hr>

<h2>Meus Empréstimos</h2>

<?php if (!empty($leitor['suspenso_ate']) && strtotime($leitor['suspenso_ate']) > time()): ?>
    <div style="background-color: #ffcccc; color: #cc0000; padding: 10px; margin-bottom: 20px; border: 1px solid #cc0000;">
        <strong>Atenção:</strong> Sua conta está suspensa até <?= date('d/m/Y', strtotime($leitor['suspenso_ate'])) ?>. 
        Você não poderá realizar novos empréstimos até esta data.
    </div>
<?php endif; ?>

<?php if (empty($emprestimos)): ?>
    <p>Você ainda não possui nenhum empréstimo registrado no seu histórico.</p>
<?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Livro</th>
                <th>Data do Empréstimo</th>
                <th>Devolução Prevista</th>
                <th>Devolução Real</th>
                <th>Renovações</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($emprestimos as $emp): ?>
                <?php 
                    $atrasado = ($emp['status'] === 'ativo' && strtotime($emp['data_prevista_devolucao']) < time());
                    $corLinha = $atrasado ? 'background-color: #ffe6e6;' : '';
                ?>
                <tr style="<?= $corLinha ?>">
                    <td><?= htmlspecialchars($emp['titulo']) ?> <br><small><?= htmlspecialchars($emp['autor']) ?></small></td>
                    <td><?= date('d/m/Y', strtotime($emp['data_emprestimo'])) ?></td>
                    <td>
                        <?= date('d/m/Y', strtotime($emp['data_prevista_devolucao'])) ?>
                        <?php if ($atrasado): ?>
                            <br><strong style="color:red;">(Atrasado)</strong>
                        <?php endif; ?>
                    </td>
                    <td><?= $emp['data_devolucao_real'] ? date('d/m/Y', strtotime($emp['data_devolucao_real'])) : '—' ?></td>
                    <td><?= $emp['renovacoes'] ?></td>
                    <td>
                        <?php if ($emp['status'] === 'ativo'): ?>
                            <span style="color: blue;">Em andamento</span>
                        <?php elseif ($emp['status'] === 'devolvido'): ?>
                            <span style="color: green;">Devolvido</span>
                        <?php else: ?>
                            <?= htmlspecialchars($emp['status']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><?= count($emprestimos) ?> empréstimo(s) no histórico.</p>
<?php endif; ?>

</body>
</html>
