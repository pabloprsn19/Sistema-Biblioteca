<?php

namespace App\Core;

class Router
{
    private $rotas = [];

    public function get($uri, $action, $guard = null)
    {
        $this->registrar('GET', $uri, $action, $guard);
    }

    public function post($uri, $action, $guard = null)
    {
        $this->registrar('POST', $uri, $action, $guard);
    }

    private function registrar($metodo, $uri, $action, $guard = null)
    {
        $pattern = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $uri);
        $pattern = '#^' . $pattern . '$#';

        $this->rotas[$metodo][] = [
            'pattern' => $pattern,
            'action'  => $action,
            'guard'   => $guard,
        ];
    }

    public function dispatch($metodo, $uri)
    {
        $uri    = '/' . trim($uri, '/');
        $metodo = strtoupper($metodo);

        $grupo = $this->rotas[$metodo] ?? [];

        foreach ($grupo as $rota) {
            if (preg_match($rota['pattern'], $uri, $matches)) {
                array_shift($matches);
                $params = array_map('urldecode', $matches);
                $this->verificarGuard($rota['guard']);
                $this->chamar($rota['action'], $params);
                return;
            }
        }

        http_response_code(404);
        echo '<h1>404 - Página não encontrada</h1>';
    }

    private function verificarGuard(?string $guard): void
    {
        if ($guard === null) {
            return;
        }

        $autenticado = !empty($_SESSION['usuario_id']);
        $perfil      = $_SESSION['usuario_perfil'] ?? '';

        if (!$autenticado) {
            header('Location: /login');
            exit;
        }

        if ($guard === 'leitor' && $perfil !== 'leitor') {
            header('Location: /');
            exit;
        }

        if ($guard === 'staff' && !in_array($perfil, ['atendente', 'administrador'], true)) {
            header('Location: /');
            exit;
        }
    }

    private function chamar($action, $params)
    {
        if (is_callable($action)) {
            call_user_func_array($action, $params);
            return;
        }

        [$classe, $metodo] = $action;

        $controller = new $classe();

        $params = array_map(fn($p) => ctype_digit($p) ? (int) $p : $p, $params);

        call_user_func_array([$controller, $metodo], $params);
    }
}
