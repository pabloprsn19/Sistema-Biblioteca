<?php

namespace App\Models;

use App\Core\Database;

class Livro
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

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

    public function listarTodos(): array
    {
        $sql = "SELECT l.id, l.titulo, l.autor, l.isbn, l.editora,
                       l.ano_publicacao, l.total_exemplares,
                       COALESCE(c.nome, 'Sem categoria') AS categoria,
                       (l.total_exemplares - (SELECT COUNT(*) FROM emprestimos WHERE livro_id = l.id AND status = 'ativo')) AS disponiveis
                FROM livros l
                LEFT JOIN categorias c ON c.id = l.categoria_id
                ORDER BY l.titulo ASC";

        return $this->db->query($sql)->fetchAll();
    }

    public function listarCategorias(): array
    {
        return $this->db->query("SELECT id, nome FROM categorias ORDER BY nome ASC")->fetchAll();
    }

    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM livros WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function atualizar(int $id, array $dados): bool
    {
        $sql = "UPDATE livros
                SET titulo           = :titulo,
                    autor            = :autor,
                    isbn             = :isbn,
                    editora          = :editora,
                    ano_publicacao   = :ano_publicacao,
                    categoria_id     = :categoria_id,
                    total_exemplares = :total_exemplares
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':titulo'           => $dados['titulo'],
            ':autor'            => $dados['autor'],
            ':isbn'             => $dados['isbn'],
            ':editora'          => $dados['editora'],
            ':ano_publicacao'   => $dados['ano_publicacao'],
            ':categoria_id'     => $dados['categoria_id'],
            ':total_exemplares' => $dados['total_exemplares'],
            ':id'               => $id,
        ]);
    }

    public function temEmprestimosAtivos(int $id): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM emprestimos WHERE livro_id = :id AND status = 'ativo'");
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function excluir(int $id): bool
    {
        try {
            $stmtEmp = $this->db->prepare("DELETE FROM emprestimos WHERE livro_id = :id");
            $stmtEmp->execute([':id' => $id]);

            $stmt = $this->db->prepare("DELETE FROM livros WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            return false;
        }
    }
}
