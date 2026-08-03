<?php

namespace App\Core;

use PDO;
use PDOException;

// Conexão com o banco de dados usando PDO.
// Padrão Singleton: garante uma única conexão durante toda a requisição.
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

            // Monta o DSN conforme o driver configurado no .env
            if ($driver === 'sqlite') {
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

                // SQLite exige ativar as foreign keys manualmente
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
