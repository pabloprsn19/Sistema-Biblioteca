<?php

namespace App\Controllers;

// controller de autenticação
// cuida do login e logout dos usuários

class AuthController
{
    // exibe a tela de login
    public function showLogin()
    {
        require __DIR__ . '/../Views/auth/login.php';
    }

    // processa o formulário de login
    public function login()
    {
        // TODO: validar email e senha com o model de usuário
        // TODO: iniciar sessão e redirecionar pro dashboard
        // TODO: tratar erro de credenciais inválidas
    }

    // encerra a sessão do usuário
    public function logout()
    {
        // TODO: destruir a sessão e mandar pro login
        session_destroy();
        header('Location: /login');
    }
}
