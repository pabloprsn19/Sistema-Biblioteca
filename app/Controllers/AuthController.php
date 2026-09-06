<?php

namespace App\Controllers;

use App\Models\Usuario;

class AuthController
{
    public function showLogin()
    {
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if ($email === '' || $senha === '') {
            $_SESSION['erro'] = 'E-mail e senha são obrigatórios.';
            header('Location: /login');
            exit;
        }

        $model = new Usuario();
        $usuario = $model->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Regenera o ID de sessão antes de armazenar dados do usuário
            session_regenerate_id(true);

            // Login bem sucedido, popula a sessão
            $_SESSION['usuario_id']     = $usuario['id'];
            $_SESSION['usuario_nome']   = $usuario['nome'];
            $_SESSION['usuario_perfil'] = $usuario['perfil'];

            // Redireciona para Início
            header('Location: /');
            exit;
        }

        // Credenciais inválidas
        $_SESSION['erro'] = 'E-mail ou senha incorretos.';
        header('Location: /login');
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
