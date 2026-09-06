<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instancia = null;

    private function __construct() {}

    public static function conectar()
    {
        if (self::$instancia === null) {
            $driver = $_ENV['DB_DRIVER'] ?? 'mysql';
            $nome   = $_ENV['DB_NAME']   ?? 'biblioteca';
            $host   = $_ENV['DB_HOST']   ?? '127.0.0.1';
            $porta  = $_ENV['DB_PORT']   ?? '3306';
            $usuario = $_ENV['DB_USER']  ?? 'root';
            $senha   = $_ENV['DB_PASS']  ?? '';

            if ($driver === 'sqlite') {
                // Resolve e normaliza caminho contra ROOT do projeto
                $nomeRelativo = ltrim($nome, '/');
                if (str_starts_with($nomeRelativo, 'htdocs/')) {
                    $nomeRelativo = substr($nomeRelativo, 7);
                }
                $baseDir = defined('ROOT') ? ROOT : dirname(__DIR__, 2);
                $nome = $baseDir . '/' . $nomeRelativo;

                $dir = dirname($nome);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }

                $dsn     = "sqlite:{$nome}";
                $usuario = null;
                $senha   = null;
            } else {
                $dsn = "mysql:host={$host};port={$porta};dbname={$nome};charset=utf8mb4";
            }

            try {
                self::$instancia = new PDO($dsn, $usuario, $senha, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);

                if ($driver === 'sqlite') {
                    self::$instancia->exec('PRAGMA foreign_keys = ON;');
                }

            } catch (PDOException $e) {
                die('Erro ao conectar com o banco: ' . $e->getMessage());
            }
        }

        return self::$instancia;
    }
}
