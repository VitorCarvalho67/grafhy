.____________________________________________________________________________________________________________
|Login -> id -> "chats acessados -> tabelas de chat por id"                                                 |
|Chats -> {} -> parâmetros de {} pela url ('se !login href = login -> chat, se =login -> chat')             |
|Privadas -> id + id                                                                                        |
|Mensagem -> mensagem('arquivo') + data + id focus após envio                                               |
|                                                                                                           |
|SigUp -> verificação email                                                                                 |
|                                                                                                           |
|index.php -> ☺                                                                                             |
|                                                                                                           |
|nav > login + 'chaves' + privadas 'AS' ==                                                                  |
|form > chaves                                                                                              |
|main > chats ->  + users_online + users_number                                                             |
|                                                                                                           |
|                                                                                                           |
|login.php -> ☺                                                                                             |
|                                                                                                           |
|nav> ==                                                                                                    |
|form> email + senha                                                                                        |
|button> cadastrar<href -> cadastro>                                                                        |
|                                                                                                           |
|cadastro.php -> ☺                                                                                          |
|                                                                                                           |
|nav> ==                                                                                                    |
|form> email + senha + userName                                                                             |
|button> cadastrar                                                                                          |
|                                                                                                           |
|                                                                                                           |
|chat.php ->                                                                                                |
|                                                                                                           |
|nav > == + nome_chat  + users_online + users_number                                                        |
|main> mensagens('diferença - id_user') -> exclusão/edição                                                  |
|form> input + submit  + anexar_arquivo                                                                     |
|                                                                                                           |
|contatos.php ->                                                                                            |
|                                                                                                           |
|nav> ==                                                                                                    |
|section> solicitacoes                                                                                      |
|main> list_contatos                                                                                        |
|footer> form_solitacoes                                                                                    |
|                                                                                                           |
|                                                                                                           |
|conversa.php ->                                                                                            |
|                                                                                                           |
|nav> == online||!online                                                                                    |
|main> mensagens('diferença - id_user') -> exclusão/edição                                                  |
|form> input + submit  + anexar_arquivos                                                                    |
|                                                                                                           |
|___________________________________________________________________________________________________________|
|                                                                                                           |
|banco_banco ->                                                                                             |
|                                                                                                           |
|tabela_users >                                                                                             |
|                                                                                                           |
|user_id --- user_name --- user_email --- user_pass --- descrição/imagem                                    |
|                                                                                                           |
|                                                                                                           |
|table_solicitacoes >                                                                                       |
|user_id_recheiv --- user_name_send --- data-hora                                                           |
|                                                                                                           |
|                                                                                                           |
|table_contatos >                                                                                           |
|user_id --- user_name                                                                                      |
|                                                                                                           |
|                                                                                                           |
|table_conexao >                                                                                            |
|mensagem_id --- mensagem --- data-hora --- user_id --- user_id1 --- user_id2                               |
|                                                                                                           |
|                                                                                                           |
|table_chats >                                                                                              |
|                                                                                                           |
|chat_id --- chat_name --- online_users --- user_number                                                     |
|                                                                                                           |
|                                                                                                           |
|table_chat * X >                                                                                           |
|                                                                                                           |
|mensagem_id --- mensagem --- data-hora --- user_id                                                         |
|                                                                                                           |
|__________________________________________________________________________________________________________.|
|                                                                                                           |
|create database banco_grafhy;                                                                              |
|use banco_grafhy;                                                                                          |
|                                                                                                           |
|                                                                                                           |
|CREATE TABLE users (                                                                                       |
|    user_id INT AUTO_INCREMENT PRIMARY KEY,                                                                |
|    user_name VARCHAR(255) NOT NULL,                                                                       |
|    user_email VARCHAR(255) NOT NULL,                                                                      |
|    user_pass VARCHAR(255) NOT NUL                                                                         |
|);                                                                                                         |
|                                                                                                           |
|CREATE TABLE solicitacoes (                                                                                |
|    id INT AUTO_INCREMENT PRIMARY KEY,                                                                     |
|    user_id_receiv INT NOT NULL,                                                                           |
|    user_name_send VARCHAR(255) NOT NULL,                                                                  |
|    data_hora DATETIME NOT NULL                                                                            |
|);                                                                                                         |
|                                                                                                           |
|CREATE TABLE contatos (                                                                                    |
|    id INT AUTO_INCREMENT PRIMARY KEY,                                                                     |
|    user_id1 INT NOT NULL,                                                                                 |
|    user_id2 INT NOT NULL                                                                                  |
|);                                                                                                         |
|                                                                                                           |
|CREATE TABLE conexao (                                                                                     |
|    mensagem_id INT AUTO_INCREMENT PRIMARY KEY,                                                            |
|    mensagem TEXT NOT NULL,                                                                                |
|    data_hora DATETIME NOT NULL,                                                                           |
|    user_id INT NOT NULL,                                                                                  |
|    user_id1 INT NOT NULL,                                                                                 |
|    user_id2 INT NOT NULL                                                                                  |
|);                                                                                                         |
|                                                                                                           |
|CREATE TABLE chats (                                                                                       |
|    chat_id INT AUTO_INCREMENT PRIMARY KEY,                                                                |
|    chat_name VARCHAR(255) NOT NULL,                                                                       |
|    online_users VARCHAR(255) NOT NULL,                                                                    |
|    user_number INT NOT NULL                                                                               |
|);                                                                                                         |
|                                                                                                           |
|CREATE TABLE chat (                                                                                        |
|    mensagem_id INT AUTO_INCREMENT PRIMARY KEY,                                                            |
|    mensagem VARCHAR(5000) NOT NULL,                                                                       |
|    data_hora VARCHAR(20) NOT NULL,                                                                        |
|    user_id INT NOT NULL                                                                                   |
|);                                                                                                         |
|                                                                                                           |
|INSERT INTO users VALUES (DEFAULT, 'Daniel', 'a@gmail.com', 'pass123');                                    |
|                                                                                                           |
|INSERT INTO chats VALUES (DEFAULT, 'chat', '1', '1');                                                      |
|                                                                                                           |
|drop table users;                                                                                          |
|___________________________________________________________________________________________________________|