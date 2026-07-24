<?php

namespace App\Core;

/*
 * Router simples pra mapear URLs pros controllers certos.
 * Suporta parâmetros dinâmicos tipo /livros/{id}.
 */
class Router
{
    private $rotas = [];

    public function get($uri, $action)
    {
        $this->registrar('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->registrar('POST', $uri, $action);
    }

    private function registrar($metodo, $uri, $action)
    {
        // converte {id} em regex de captura
        $pattern = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $uri);
        $pattern = '#^' . $pattern . '$#';

        $this->rotas[$metodo][] = [
            'pattern' => $pattern,
            'action'  => $action,
        ];
    }

    public function dispatch($metodo, $uri)
    {
        $uri    = '/' . trim($uri, '/');
        $metodo = strtoupper($metodo);

        $grupo = $this->rotas[$metodo] ?? [];

        foreach ($grupo as $rota) {
            if (preg_match($rota['pattern'], $uri, $matches)) {
                array_shift($matches); // tira o match completo
                $params = array_map('urldecode', $matches);
                $this->chamar($rota['action'], $params);
                return;
            }
        }

        // nenhuma rota encontrada
        http_response_code(404);
        echo '<h1>404 - Página não encontrada</h1>';
    }

    private function chamar($action, $params)
    {
        if (is_callable($action)) {
            call_user_func_array($action, $params);
            return;
        }

        [$classe, $metodo] = $action;

        $controller = new $classe();

        // converte pra int se for número
        $params = array_map(fn($p) => ctype_digit($p) ? (int) $p : $p, $params);

        call_user_func_array([$controller, $metodo], $params);
    }
}
