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
        $sql = "SELECT e.id, e.data_emprestimo, e.data_prevista_devolucao, e.data_devolucao_real,
                       e.status, e.renovacoes, l.titulo, l.autor
                FROM emprestimos e
                INNER JOIN livros l ON l.id = e.livro_id
                WHERE e.usuario_id = :usuario_id
                ORDER BY e.data_emprestimo DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll();
    }

    public function listarTodos(string $status = ''): array
    {
        $where = $status ? "WHERE e.status = :status" : "";
        $sql = "SELECT e.id, e.data_emprestimo, e.data_prevista_devolucao,
                       e.data_devolucao_real, e.status, e.renovacoes,
                       l.titulo, l.autor,
                       u.nome AS usuario_nome, u.email AS usuario_email,
                       a.nome AS atendente_nome
                FROM emprestimos e
                INNER JOIN livros   l ON l.id = e.livro_id
                INNER JOIN usuarios u ON u.id = e.usuario_id
                LEFT JOIN  usuarios a ON a.id = e.atendente_id
                {$where}
                ORDER BY e.data_emprestimo DESC";

        if ($status) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':status' => $status]);
            return $stmt->fetchAll();
        }
        return $this->db->query($sql)->fetchAll();
    }

    public function listarAtrasados(): array
    {
        $driver = $_ENV['DB_DRIVER'] ?? 'sqlite';
        $hoje   = $driver === 'sqlite' ? "date('now')" : "CURDATE()";

        $sql = "SELECT e.id, e.data_emprestimo, e.data_prevista_devolucao,
                       l.titulo, l.autor,
                       u.nome AS usuario_nome, u.email AS usuario_email
                FROM emprestimos e
                INNER JOIN livros   l ON l.id = e.livro_id
                INNER JOIN usuarios u ON u.id = e.usuario_id
                WHERE e.status = 'ativo'
                  AND e.data_prevista_devolucao < {$hoje}
                ORDER BY e.data_prevista_devolucao ASC";

        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): array|false
    {
        $sql = "SELECT e.*, l.titulo, l.autor, l.isbn,
                       u.nome AS usuario_nome, u.email AS usuario_email,
                       a.nome AS atendente_nome
                FROM emprestimos e
                INNER JOIN livros   l ON l.id = e.livro_id
                INNER JOIN usuarios u ON u.id = e.usuario_id
                LEFT JOIN  usuarios a ON a.id = e.atendente_id
                WHERE e.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function emprestar(int $usuarioId, int $livroId, ?int $atendenteId = null): bool
    {
        $driver = $_ENV['DB_DRIVER'] ?? 'sqlite';
        if ($driver === 'sqlite') {
            $hoje  = "date('now')";
            $prazo = "date('now', '+14 days')";
        } else {
            $hoje  = "CURDATE()";
            $prazo = "DATE_ADD(CURDATE(), INTERVAL 14 DAY)";
        }

        $sql = "INSERT INTO emprestimos
                    (usuario_id, livro_id, atendente_id, data_emprestimo, data_prevista_devolucao, status)
                VALUES
                    (:usuario_id, :livro_id, :atendente_id, {$hoje}, {$prazo}, 'ativo')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':usuario_id'   => $usuarioId,
            ':livro_id'     => $livroId,
            ':atendente_id' => $atendenteId,
        ]);
    }

    public function devolverPorLivro(int $livroId, int $usuarioId): bool
    {
        $driver = $_ENV['DB_DRIVER'] ?? 'sqlite';
        $hoje   = $driver === 'sqlite' ? "date('now')" : "CURDATE()";

        $sql = "UPDATE emprestimos
                SET status = 'devolvido', data_devolucao_real = {$hoje}
                WHERE livro_id   = :livro_id
                  AND usuario_id = :usuario_id
                  AND status     = 'ativo'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':livro_id'   => $livroId,
            ':usuario_id' => $usuarioId,
        ]);
    }

    public function devolver(int $emprestimoId): bool
    {
        $driver = $_ENV['DB_DRIVER'] ?? 'sqlite';
        $hoje   = $driver === 'sqlite' ? "date('now')" : "CURDATE()";

        $sql = "UPDATE emprestimos
                SET status = 'devolvido', data_devolucao_real = {$hoje}
                WHERE id = :id AND status = 'ativo'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $emprestimoId]);
    }

    public function renovar(int $emprestimoId): bool
    {
        // Verifica se já atingiu o limite de 2 renovações e se não está atrasado
        $emprestimo = $this->buscarPorId($emprestimoId);
        if (!$emprestimo || $emprestimo['status'] !== 'ativo') {
            return false;
        }
        if ($emprestimo['renovacoes'] >= 2) {
            return false;
        }

        $driver = $_ENV['DB_DRIVER'] ?? 'sqlite';
        if ($driver === 'sqlite') {
            $novoPrazo = "date(data_prevista_devolucao, '+7 days')";
        } else {
            $novoPrazo = "DATE_ADD(data_prevista_devolucao, INTERVAL 7 DAY)";
        }

        $sql = "UPDATE emprestimos
                SET data_prevista_devolucao = {$novoPrazo},
                    renovacoes = renovacoes + 1
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $emprestimoId]);
    }

    public function buscarAtivosPorUsuario(int $usuarioId): array
    {
        $sql = "SELECT livro_id FROM emprestimos WHERE usuario_id = :usuario_id AND status = 'ativo'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

    public function totalAtivos(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM emprestimos WHERE status = 'ativo'")->fetchColumn();
    }

    public function totalAtrasados(): int
    {
        $driver = $_ENV['DB_DRIVER'] ?? 'sqlite';
        $hoje   = $driver === 'sqlite' ? "date('now')" : "CURDATE()";
        return (int) $this->db->query(
            "SELECT COUNT(*) FROM emprestimos WHERE status = 'ativo' AND data_prevista_devolucao < {$hoje}"
        )->fetchColumn();
    }

    public function maisEmprestados(int $limite = 10): array
    {
        $sql = "SELECT l.titulo, l.autor, COUNT(e.id) AS total_emprestimos
                FROM emprestimos e
                INNER JOIN livros l ON l.id = e.livro_id
                GROUP BY e.livro_id
                ORDER BY total_emprestimos DESC
                LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
