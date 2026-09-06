Sistema de Gerenciamento de Biblioteca

Projeto desenvolvido para a disciplina de Projeto e Implementação de Sistemas Web II.
Aluno: Pablo Rodrigo de Souza Nascimento


Apresentação

Este sistema é uma aplicação web para gerenciamento de biblioteca, permitindo o
controle do acervo de livros, empréstimos, devoluções, controle de acesso por perfil
de usuário e geração de relatórios de acompanhamento.

A aplicação foi construída em PHP puro seguindo a arquitetura MVC, utilizando PDO
para conexão com banco de dados (suportando SQLite em ambiente local e MySQL em
produção).


Perfis de Acesso

O sistema conta com três perfis de usuários com diferentes permissões:

- Administrador: Acesso completo para gerenciar usuários, livros, empréstimos,
  relatórios e suspensões de leitores.

- Atendente: Gerencia o acervo, realiza registros de empréstimos e devoluções
  e visualiza relatórios.

- Leitor: Consulta o catálogo, solicita empréstimos e renovações do próprio acervo.


Credenciais para Teste

- Administrador : admin@biblioteca.com       (senha: admin123)
- Atendente     : atendente@biblioteca.com   (senha: atendente123)
- Leitor (João) : joao@email.com             (senha: leitor123)
- Leitor (Maria): maria@email.com            (senha: leitor123)


Como Executar o Projeto

Pré-requisitos:
- PHP 8.1 ou superior (com extensões pdo_sqlite e pdo_mysql ativas)
- Git

Execução Local:

1. Clone o repositório e acesse a pasta do projeto:
   git clone https://github.com/pabloprsn19/Sistema-Biblioteca.git
   cd Sistema-Biblioteca

2. Copie o arquivo de exemplo de ambiente:
   cp .env.example .env

3. Execute o script de configuração inicial para criar as tabelas e dados de teste:
   php setup.php

4. Inicie o servidor embutido do PHP:
   php -S 127.0.0.1:9090 -t public

Após iniciar o servidor, acesse http://127.0.0.1:9090 no seu navegador.


Deploy no Railway

O projeto está pronto para ser hospedado na plataforma Railway:

1. Conecte o repositório do GitHub ao projeto no Railway.
2. Adicione o plugin de banco de dados MySQL no painel.
3. Defina as variáveis de ambiente no Railway:
   - DB_DRIVER=mysql
   - DB_HOST, DB_PORT, DB_USER, DB_PASS e DB_NAME com as credenciais do MySQL.
4. Execute php setup.php para criar a estrutura e a carga inicial no banco remoto.


Estrutura de Pastas

- app/Controllers/ : Lógica de controle das requisições e regras de aplicação
- app/Models/      : Modelagem e comunicação com o banco de dados via PDO
- app/Views/       : Telas e componentes em HTML/PHP
- app/Core/        : Roteador de URLs e classe de conexão com o banco
- public/          : Ponto de entrada (index.php) e arquivos estáticos (CSS)
- routes/          : Mapeamento das rotas do sistema
- database/        : Banco SQLite local (não versionado)
- .env.example     : Modelo de configuração de ambiente
- setup.php        : Script de instalação e seed do banco de dados


Rotas Principais

- GET  /login                        Público      Tela de autenticação
- GET  /                             Todos        Dashboard
- GET  /livros                       Todos        Catálogo de livros
- GET  /livros/novo                  Staff        Cadastrar novo livro
- GET  /emprestimos                  Staff        Listar empréstimos
- GET  /emprestimos/novo             Staff        Registrar empréstimo
- GET  /emprestimos/{id}/devolver    Staff        Registrar devolução
- GET  /emprestimos/{id}/renovar     Staff/Leitor Renovar prazo
- GET  /relatorios                   Staff        Relatórios gerais
- GET  /usuarios                     Admin        Gerenciar usuários
- GET  /meus-emprestimos             Leitor       Meus empréstimos
