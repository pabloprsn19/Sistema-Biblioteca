Sistema de Gerenciamento de Biblioteca

Projeto desenvolvido para a disciplina de Projeto e Implementação de Sistemas Web II.
Aluno: Pablo Rodrigo de Souza Nascimento


Apresentação

Este sistema é uma aplicação web para gerenciamento de biblioteca, permitindo o controle do acervo de livros, empréstimos, devoluções, controle de acessos de usuários por perfil e geração de relatórios de acompanhamento.

A aplicação foi construída em PHP puro seguindo a arquitetura MVC, utilizando PDO para conexão com banco de dados (suportando SQLite em ambiente local e MySQL em produção).


Perfis de Acesso

O sistema conta com três perfis de usuários com diferentes permissões:

- Administrador: Acesso completo para gerenciar usuários, livros, empréstimos, relatórios e suspensões de leitores.
- Atendente: Gerencia o acervo, realiza registros de empréstimos/devoluções e visualiza relatórios.
- Leitor: Consulta o catálogo, solicita empréstimos e renovações do seu próprio acervo.


Credenciais para Teste

- Administrador: admin@biblioteca.com (senha: admin123)
- Atendente: atendente@biblioteca.com (senha: atendente123)
- Leitores: joao@email.com / maria@email.com (senha: leitor123)


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
3. Defina as variáveis de ambiente (DB_DRIVER=mysql, DB_HOST, DB_USER, DB_PASS e DB_NAME) com as credenciais fornecidas pelo MySQL no Railway.
4. Execute php setup.php para criar a estrutura e a carga inicial no banco de dados remoto.


Estrutura de Pastas

- app/Controllers/ : Lógica de controle das requisições e regras de aplicação
- app/Models/ : Modelagem e comunicação com o banco de dados via PDO
- app/Views/ : Telas e componentes em HTML/PHP
- app/Core/ : Roteador de URLs e classe de conexão com o banco
- public/ : Ponto de entrada (index.php) e arquivos estáticos (CSS)
- routes/ : Mapeamento das rotas do sistema
- docs/ : Documentação complementar


Documentação

Informações detalhadas sobre o funcionamento e deploy estão disponíveis na pasta docs:
- Manual do Usuário: docs/manual_usuario.md
- Guia de Instalação e Deploy: docs/guia_instalacao_deploy.md
- Tecnologias Utilizadas: docs/tecnologias.md
