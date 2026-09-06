<?php
/**
 * setup.php — Inicializa o banco de dados (SQLite ou MySQL)
 * Uso: php setup.php ou via navegador (https://seu-site.com/setup.php)
 */

if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}

// Carregar .env
$env = __DIR__ . '/.env';
if (file_exists($env)) {
    foreach (file($env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        if (str_starts_with(trim($linha), '#') || !str_contains($linha, '=')) continue;
        [$chave, $valor] = explode('=', $linha, 2);
        $_ENV[trim($chave)] = trim($valor);
    }
}

$driver  = $_ENV['DB_DRIVER'] ?? 'sqlite';
$nome    = $_ENV['DB_NAME']   ?? __DIR__ . '/database/biblioteca.db';
$host    = $_ENV['DB_HOST']   ?? '127.0.0.1';
$porta   = $_ENV['DB_PORT']   ?? '3306';
$usuario = $_ENV['DB_USER']   ?? 'root';
$senha   = $_ENV['DB_PASS']   ?? '';

// Resolve caminho relativo do SQLite contra o diretório do setup.php
if ($driver === 'sqlite' && !str_starts_with($nome, '/')) {
    $nome = __DIR__ . '/' . $nome;
}

// Conectar ao banco de dados
try {
    if ($driver === 'sqlite') {
        $dir = dirname($nome);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $db = new PDO("sqlite:{$nome}");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec('PRAGMA foreign_keys = ON;');
        echo "Conectado: SQLite em {$nome}\n";
    } else {
        $dsn = "mysql:host={$host};port={$porta};charset=utf8mb4";
        $db  = new PDO($dsn, $usuario, $senha, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $db->exec("CREATE DATABASE IF NOT EXISTS `{$nome}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $db->exec("USE `{$nome}`");
        echo "Conectado: MySQL em {$host}:{$porta} / {$nome}\n";
    }
} catch (PDOException $e) {
    echo "Erro ao conectar: " . $e->getMessage() . "\n";
    exit(1);
}

// Helpers de SQL por driver
if ($driver === 'sqlite') {
    $autoInc    = 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $intPk      = 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $datetimeNow = "DATETIME DEFAULT (datetime('now'))";
    $dateNow    = "(datetime('now'))";
} else {
    $autoInc    = 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY';
    $intPk      = 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY';
    $datetimeNow = 'DATETIME DEFAULT CURRENT_TIMESTAMP';
    $dateNow    = 'CURRENT_TIMESTAMP';
}

// Criar tabelas
$db->exec("
    CREATE TABLE IF NOT EXISTS categorias (
        id   {$autoInc},
        nome TEXT NOT NULL
    );
");

if ($driver === 'mysql') {
    $db->exec("
        CREATE TABLE IF NOT EXISTS livros (
            id               {$autoInc},
            titulo           TEXT    NOT NULL,
            autor            TEXT    NOT NULL,
            isbn             VARCHAR(20) NOT NULL,
            editora          TEXT,
            ano_publicacao   INT,
            categoria_id     INT UNSIGNED REFERENCES categorias(id) ON DELETE SET NULL,
            total_exemplares INT NOT NULL DEFAULT 1,
            criado_em        {$datetimeNow}
        );
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id           {$autoInc},
            nome         TEXT    NOT NULL,
            email        VARCHAR(191) NOT NULL UNIQUE,
            senha        TEXT    NOT NULL,
            perfil       VARCHAR(20) NOT NULL DEFAULT 'leitor',
            ativo        TINYINT NOT NULL DEFAULT 1,
            suspenso_ate DATE,
            criado_em    {$datetimeNow},
            atualizado_em {$datetimeNow}
        );
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS emprestimos (
            id                      {$autoInc},
            usuario_id              INT UNSIGNED NOT NULL REFERENCES usuarios(id),
            livro_id                INT UNSIGNED NOT NULL REFERENCES livros(id),
            atendente_id            INT UNSIGNED REFERENCES usuarios(id),
            data_emprestimo         DATE    NOT NULL,
            data_prevista_devolucao DATE    NOT NULL,
            data_devolucao_real     DATE,
            status                  VARCHAR(20) NOT NULL DEFAULT 'ativo',
            renovacoes              INT NOT NULL DEFAULT 0
        );
    ");
} else {
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
}

echo "Tabelas criadas.\n";

// Carga inicial (seeds)
$totalCat = (int) $db->query("SELECT count(*) FROM categorias")->fetchColumn();
if ($totalCat === 0) {
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
            ('1984',                    'George Orwell',       '978-0-452-28423-4', 'Signet Classic', 1949, 1, 3),
            ('Dom Quixote',             'Miguel de Cervantes', '978-85-325-2366-4', 'Editora Record', 1605, 2, 2),
            ('Clean Code',              'Robert C. Martin',    '978-0-13-235088-4', 'Prentice Hall',  2008, 3, 5),
            ('Sapiens',                 'Yuval Noah Harari',   '978-85-8057-388-2', 'L&PM',           2011, 4, 4),
            ('A República',             'Platão',              '978-85-7706-038-3', 'Martin Claret',  NULL, 5, 2),
            ('O Senhor dos Anéis',      'J.R.R. Tolkien',      '978-85-325-1513-3', 'Martins Fontes', 1954, 1, 3),
            ('Harry Potter e a Pedra Filosofal', 'J.K. Rowling', '978-85-325-2183-7', 'Rocco',        1997, 2, 4);
    ");
    echo "Livros de exemplo inseridos.\n";
} else {
    echo "Livros já existem — nada inserido.\n";
}

$totalUsuarios = (int) $db->query("SELECT count(*) FROM usuarios")->fetchColumn();
if ($totalUsuarios === 0) {
    $hashAdmin     = password_hash('admin123',     PASSWORD_DEFAULT);
    $hashAtendente = password_hash('atendente123', PASSWORD_DEFAULT);
    $hashLeitor    = password_hash('leitor123',    PASSWORD_DEFAULT);
    $hashLeitor2   = password_hash('leitor123',    PASSWORD_DEFAULT);

    $stmt = $db->prepare("
        INSERT INTO usuarios (nome, email, senha, perfil) VALUES (:nome, :email, :senha, :perfil)
    ");

    $stmt->execute([':nome' => 'Administrador',   ':email' => 'admin@biblioteca.com',      ':senha' => $hashAdmin,     ':perfil' => 'administrador']);
    $stmt->execute([':nome' => 'Ana Atendente',   ':email' => 'atendente@biblioteca.com',  ':senha' => $hashAtendente, ':perfil' => 'atendente']);
    $stmt->execute([':nome' => 'João Leitor',     ':email' => 'joao@email.com',            ':senha' => $hashLeitor,    ':perfil' => 'leitor']);
    $stmt->execute([':nome' => 'Maria Leitora',   ':email' => 'maria@email.com',           ':senha' => $hashLeitor2,   ':perfil' => 'leitor']);

    echo "Usuários criados:\n";
    echo "  admin@biblioteca.com      / admin123      (administrador)\n";
    echo "  atendente@biblioteca.com  / atendente123  (atendente)\n";
    echo "  joao@email.com            / leitor123     (leitor)\n";
    echo "  maria@email.com           / leitor123     (leitor)\n";
} else {
    echo "Usuários já existem — nada inserido.\n";
}

echo "\nBanco configurado com sucesso!\n";
echo "Servidor local: php -S 127.0.0.1:9090 -t public\n";
echo "Acesse:         http://127.0.0.1:9090\n";
