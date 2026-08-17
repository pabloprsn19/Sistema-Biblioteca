<?php

$env = __DIR__ . '/.env';
if (file_exists($env)) {
    foreach (file($env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        if (str_starts_with(trim($linha), '#') || !str_contains($linha, '=')) continue;
        [$chave, $valor] = explode('=', $linha, 2);
        $_ENV[trim($chave)] = trim($valor);
    }
}

$driver = $_ENV['DB_DRIVER'] ?? 'sqlite';
$nome   = $_ENV['DB_NAME']   ?? '/tmp/biblioteca_demo.db';

if ($driver !== 'sqlite') {
    echo "Este script só suporta SQLite. Configure DB_DRIVER=sqlite no .env\n";
    exit(1);
}

try {
    $db = new PDO("sqlite:{$nome}");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('PRAGMA foreign_keys = ON;');

    $db->exec("
        CREATE TABLE IF NOT EXISTS categorias (
            id   INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL
        );
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS livros (
            id               INTEGER PRIMARY KEY AUTOINCREMENT,
            titulo           TEXT    NOT NULL,
            autor            TEXT    NOT NULL,
            isbn             TEXT    NOT NULL,
            editora          TEXT,
            ano_publicacao   INTEGER,
            categoria_id     INTEGER REFERENCES categorias(id) ON DELETE SET NULL,
            total_exemplares INTEGER NOT NULL DEFAULT 1,
            criado_em        DATETIME DEFAULT (datetime('now'))
        );
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id           INTEGER PRIMARY KEY AUTOINCREMENT,
            nome         TEXT    NOT NULL,
            email        TEXT    NOT NULL UNIQUE,
            senha        TEXT    NOT NULL,
            perfil       TEXT    NOT NULL DEFAULT 'leitor',
            ativo        INTEGER NOT NULL DEFAULT 1,
            suspenso_ate DATE,
            criado_em    DATETIME DEFAULT (datetime('now')),
            atualizado_em DATETIME DEFAULT (datetime('now'))
        );
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS emprestimos (
            id                      INTEGER PRIMARY KEY AUTOINCREMENT,
            usuario_id              INTEGER NOT NULL REFERENCES usuarios(id),
            livro_id                INTEGER NOT NULL REFERENCES livros(id),
            atendente_id            INTEGER REFERENCES usuarios(id),
            data_emprestimo         DATE    NOT NULL,
            data_prevista_devolucao DATE    NOT NULL,
            data_devolucao_real     DATE,
            status                  TEXT    NOT NULL DEFAULT 'ativo',
            renovacoes              INTEGER NOT NULL DEFAULT 0
        );
    ");

    echo "Tabelas criadas.\n";

    $totalCategorias = (int) $db->query("SELECT count(*) FROM categorias")->fetchColumn();
    if ($totalCategorias === 0) {
        $db->exec("
            INSERT INTO categorias (nome) VALUES
                ('Ficção Científica'),
                ('Romance'),
                ('Tecnologia'),
                ('História'),
                ('Filosofia');
        ");
        echo "Categorias inseridas.\n";
    } else {
        echo "Categorias já existem — nada inserido.\n";
    }

    $totalLivros = (int) $db->query("SELECT count(*) FROM livros")->fetchColumn();
    if ($totalLivros === 0) {
        $db->exec("
            INSERT INTO livros (titulo, autor, isbn, editora, ano_publicacao, categoria_id, total_exemplares) VALUES
                ('1984',        'George Orwell',       '978-0-452-28423-4', 'Signet Classic', 1949, 1, 3),
                ('Dom Quixote', 'Miguel de Cervantes', '978-85-325-2366-4', 'Editora Record', 1605, 2, 2),
                ('Clean Code',  'Robert C. Martin',    '978-0-13-235088-4', 'Prentice Hall',  2008, 3, 5),
                ('Sapiens',     'Yuval Noah Harari',   '978-85-8057-388-2', 'L&PM',           2011, 4, 4),
                ('A República', 'Platão',              '978-85-7706-038-3', 'Martin Claret',  NULL, 5, 2);
        ");
        echo "Livros de exemplo inseridos.\n";
    } else {
        echo "Livros já existem — nada inserido.\n";
    }

    $totalUsuarios = (int) $db->query("SELECT count(*) FROM usuarios")->fetchColumn();
    if ($totalUsuarios === 0) {
        $hashAdmin   = password_hash('admin123', PASSWORD_DEFAULT);
        $hashLeitor  = password_hash('leitor123', PASSWORD_DEFAULT);

        $stmt = $db->prepare("
            INSERT INTO usuarios (nome, email, senha, perfil) VALUES
                (:nome, :email, :senha, :perfil)
        ");

        $stmt->execute([':nome' => 'Administrador', ':email' => 'admin@biblioteca.com',   ':senha' => $hashAdmin,  ':perfil' => 'administrador']);
        $stmt->execute([':nome' => 'João Leitor',   ':email' => 'joao@email.com',          ':senha' => $hashLeitor, ':perfil' => 'leitor']);

        echo "Usuários criados.\n";
        echo "  admin@biblioteca.com    / admin123  (administrador)\n";
     echo "  joao@email.com          / leitor123 (leitor)\n";
    } else {
        echo "Usuários já existem — nada inserido.\n";
    }

    echo "\nBanco configurado em: {$nome}\n";
    echo "Agora execute: bash iniciar.sh\n";
    echo "Acesse:        http://127.0.0.1:9090\n";

} catch (PDOException $e) {
    echo "Erro ao configurar o banco: " . $e->getMessage() . "\n";
    exit(1);
}
