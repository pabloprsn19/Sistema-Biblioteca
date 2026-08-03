<?php

namespace App\Models;

use App\Core\Database;

// Model responsável pela comunicação com a tabela `livros`
class Livro
{
    private $db;

    public function __construct()
    {
        // Obtém a conexão PDO via Singleton
        $this->db = Database::conectar();
    }

    // Insere um livro novo no banco
    // Usa Prepared Statements para evitar SQL Injection
    public function inserir(array $dados): bool
    {
        $sql = "INSERT INTO livros (titulo, autor, isbn, editora, ano_publicacao, categoria_id, total_exemplares)
                VALUES (:titulo, :autor, :isbn, :editora, :ano_publicacao, :categoria_id, :total_exemplares)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':titulo'           => $dados['titulo'],
            ':autor'            => $dados['autor'],
            ':isbn'             => $dados['isbn'],
            ':editora'          => $dados['editora'],
            ':ano_publicacao'   => $dados['ano_publicacao'],
            ':categoria_id'     => $dados['categoria_id'],
            ':total_exemplares' => $dados['total_exemplares'],
        ]);
    }

    // Retorna todos os livros, com o nome da categoria via JOIN
    public function listarTodos(): array
    {
        $sql = "SELECT l.id, l.titulo, l.autor, l.isbn, l.editora,
                       l.ano_publicacao, l.total_exemplares,
                       COALESCE(c.nome, 'Sem categoria') AS categoria
                FROM livros l
                LEFT JOIN categorias c ON c.id = l.categoria_id
                ORDER BY l.titulo ASC";

        return $this->db->query($sql)->fetchAll();
    }

    // Retorna as categorias para popular o <select> do formulário
    public function listarCategorias(): array
    {
        return $this->db->query("SELECT id, nome FROM categorias ORDER BY nome ASC")->fetchAll();
    }
}
