<?php

namespace App\Models;

use App\Core\Database;

class Usuario
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function buscarPorEmail(string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function listarTodos(): array
    {
        $sql = "SELECT id, nome, email, perfil, ativo, suspenso_ate, criado_em
                FROM usuarios
                ORDER BY nome ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function inserir(array $dados): bool
    {
        $sql = "INSERT INTO usuarios (nome, email, senha, perfil, ativo)
                VALUES (:nome, :email, :senha, :perfil, 1)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome'   => $dados['nome'],
            ':email'  => $dados['email'],
            ':senha'  => password_hash($dados['senha'], PASSWORD_DEFAULT),
            ':perfil' => $dados['perfil'],
        ]);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $sql = "UPDATE usuarios
                SET nome   = :nome,
                    email  = :email,
                    perfil = :perfil
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome'   => $dados['nome'],
            ':email'  => $dados['email'],
            ':perfil' => $dados['perfil'],
            ':id'     => $id,
        ]);
    }

    public function alterarSenha(int $id, string $novaSenha): bool
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET senha = :senha WHERE id = :id");
        return $stmt->execute([
            ':senha' => password_hash($novaSenha, PASSWORD_DEFAULT),
            ':id'    => $id,
        ]);
    }

    public function suspender(int $id, string $ate): bool
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET suspenso_ate = :ate WHERE id = :id");
        return $stmt->execute([':ate' => $ate, ':id' => $id]);
    }

    public function reativar(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET suspenso_ate = NULL, ativo = 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function desativar(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET ativo = 0 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function emailJaExiste(string $email, ?int $excluirId = null): bool
    {
        if ($excluirId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email AND id != :id");
            $stmt->execute([':email' => $email, ':id' => $excluirId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
            $stmt->execute([':email' => $email]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }
}
