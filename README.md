Sistema de Gerenciamento de Biblioteca

Projeto para a disciplina de Projeto e Implementacao de Sistemas Web II.

Tecnologias Utilizadas:
- PHP 8 (sem frameworks)
- Arquitetura MVC com roteador proprio
- SQLite via PDO (banco local para facilitar testes, sem instalacao)
- HTML 

Como rodar o projeto:

1. Preparar o banco de dados
Abra o terminal na pasta do projeto e rode:
php setup.php

Isso vai criar o arquivo do banco e inserir os dados de exemplo.

2. Subir o servidor
No terminal, rode o script:
bash iniciar.sh

3. Acessar
Acesse no navegador: http://127.0.0.1:9090

Usuarios gerados para teste:
- Administrador: admin@biblioteca.com (Senha: admin123)
- Leitor: joao@email.com (Senha: leitor123)

O que esta funcionando:
- Cadastro de livros (com validacao de titulo, autor e isbn)
- Listagem geral (trazendo o nome da categoria)
- Edicao de livros existentes
- Exclusao
- Mensagens visuais de erro/sucesso usando sessoes PHP

Aluno: Pablo Rodrigo de Souza Nascimento
