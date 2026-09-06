<?php

namespace App\Controllers;

use App\Models\Usuario;

class UsuarioController
{
    public function index()
    {
        $model    = new Usuario();
        $usuarios = $model->listarTodos();
        require __DIR__ . '/../Views/usuarios/listar.php';
    }

    public function create()
    {
        require __DIR__ . '/../Views/usuarios/criar.php';
    }

    public function store()
    {
        $nome   = trim($_POST['nome']   ?? '');
        $email  = trim($_POST['email']  ?? '');
        $senha  = $_POST['senha']       ?? '';
        $perfil = $_POST['perfil']      ?? '';

        $perfisValidos = ['leitor', 'atendente', 'administrador'];

        if ($nome === '' || $email === '' || $senha === '' || !in_array($perfil, $perfisValidos, true)) {
            $_SESSION['erro'] = 'Todos os campos são obrigatórios e o perfil deve ser válido.';
            header('Location: /usuarios/novo');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erro'] = 'E-mail inválido.';
            header('Location: /usuarios/novo');
            exit;
        }

        if (strlen($senha) < 6) {
            $_SESSION['erro'] = 'A senha deve ter no mínimo 6 caracteres.';
            header('Location: /usuarios/novo');
            exit;
        }

        $model = new Usuario();
        if ($model->emailJaExiste($email)) {
            $_SESSION['erro'] = 'Já existe um usuário com este e-mail.';
            header('Location: /usuarios/novo');
            exit;
        }

        $ok = $model->inserir(compact('nome', 'email', 'senha', 'perfil'));
        if ($ok) {
            $_SESSION['sucesso'] = 'Usuário cadastrado com sucesso!';
            header('Location: /usuarios');
        } else {
            $_SESSION['erro'] = 'Erro ao cadastrar o usuário. Tente novamente.';
            header('Location: /usuarios/novo');
        }
        exit;
    }

    public function edit(int $id)
    {
        $model   = new Usuario();
        $usuario = $model->buscarPorId($id);

        if (!$usuario) {
            $_SESSION['erro'] = 'Usuário não encontrado.';
            header('Location: /usuarios');
            exit;
        }

        require __DIR__ . '/../Views/usuarios/editar.php';
    }

    public function update(int $id)
    {
        $nome   = trim($_POST['nome']   ?? '');
        $email  = trim($_POST['email']  ?? '');
        $perfil = $_POST['perfil']      ?? '';
        $senha  = $_POST['senha']       ?? '';

        $perfisValidos = ['leitor', 'atendente', 'administrador'];

        if ($nome === '' || $email === '' || !in_array($perfil, $perfisValidos, true)) {
            $_SESSION['erro'] = 'Nome, e-mail e perfil são obrigatórios.';
            header("Location: /usuarios/{$id}/editar");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erro'] = 'E-mail inválido.';
            header("Location: /usuarios/{$id}/editar");
            exit;
        }

        $model = new Usuario();
        if ($model->emailJaExiste($email, $id)) {
            $_SESSION['erro'] = 'Já existe outro usuário com este e-mail.';
            header("Location: /usuarios/{$id}/editar");
            exit;
        }

        $ok = $model->atualizar($id, compact('nome', 'email', 'perfil'));

        if ($ok && $senha !== '') {
            if (strlen($senha) < 6) {
                $_SESSION['erro'] = 'A senha deve ter no mínimo 6 caracteres.';
                header("Location: /usuarios/{$id}/editar");
                exit;
            }
            $model->alterarSenha($id, $senha);
        }

        if ($ok) {
            $_SESSION['sucesso'] = 'Usuário atualizado com sucesso!';
            header('Location: /usuarios');
        } else {
            $_SESSION['erro'] = 'Erro ao atualizar o usuário. Tente novamente.';
            header("Location: /usuarios/{$id}/editar");
        }
        exit;
    }

    public function suspender(int $id)
    {
        $ate = $_POST['suspenso_ate'] ?? '';

        if (empty($ate)) {
            $_SESSION['erro'] = 'Informe a data de suspensão.';
            header("Location: /usuarios/{$id}/editar");
            exit;
        }

        $model = new Usuario();
        $ok    = $model->suspender($id, $ate);

        if ($ok) {
            $_SESSION['sucesso'] = 'Usuário suspenso até ' . date('d/m/Y', strtotime($ate)) . '.';
        } else {
            $_SESSION['erro'] = 'Erro ao suspender o usuário.';
        }

        header('Location: /usuarios');
        exit;
    }

    public function reativar(int $id)
    {
        $model = new Usuario();
        $ok    = $model->reativar($id);

        if ($ok) {
            $_SESSION['sucesso'] = 'Usuário reativado com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao reativar o usuário.';
        }

        header('Location: /usuarios');
        exit;
    }

    public function desativar(int $id)
    {
        $model = new Usuario();
        $ok    = $model->desativar($id);

        if ($ok) {
            $_SESSION['sucesso'] = 'Usuário desativado com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao desativar o usuário.';
        }

        header('Location: /usuarios');
        exit;
    }
}
