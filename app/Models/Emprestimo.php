<?php

namespace App\Models;

use App\Core\Database;

class Emprestimo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function listarPorUsuario(int $usuarioId): array
    {
        $sql = "SELECT e.id, e.data_emprestimo, e.data_prevista_devolucao, e.data_devolucao_real, e.status, e.renovacoes,
                       l.titulo, l.autor
                FROM emprestimos e
                INNER JOIN livros l ON l.id = e.livro_id
                WHERE e.usuario_id = :usuario_id
                ORDER BY e.data_emprestimo DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        
        return $stmt->fetchAll();
    }

    public function emprestar(int $usuarioId, int $livroId): bool
    {
        $sql = "INSERT INTO emprestimos (usuario_id, livro_id, data_emprestimo, data_prevista_devolucao, status)
                VALUES (:usuario_id, :livro_id, date('now'), date('now', '+7 days'), 'ativo')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':livro_id'   => $livroId
        ]);
    }

    public function devolverPorLivro(int $livroId, int $usuarioId): bool
    {
        $sql = "UPDATE emprestimos 
                SET status = 'devolvido', data_devolucao_real = date('now')
                WHERE livro_id = :livro_id AND usuario_id = :usuario_id AND status = 'ativo'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':livro_id'   => $livroId,
            ':usuario_id' => $usuarioId
        ]);
    }

    public function buscarAtivosPorUsuario(int $usuarioId): array
    {
        $sql = "SELECT livro_id FROM emprestimos WHERE usuario_id = :usuario_id AND status = 'ativo'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        // Retorna apenas um array plano com os IDs dos livros
        return $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
    }
}
