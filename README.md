## Como rodar o projeto?

#### Dependências necessárias:
<a href="https://nodejs.org/en/">Node.js</a> (versão recomendada: 18.15.0 LTS)
<br>
<a href="https://www.php.net/downloads">PHP</a> ^8.1
<br>
<a href="https://getcomposer.org/download/">Composer</a><br>
<a href="https://dev.mysql.com/downloads/installer/">MySQL</a>
<br>

(Recomendo o uso do <a href="https://www.apachefriends.org/pt_br/download.html">XAMPP</a> (^8.1.12) para rodar o MySQL e PHP)

#### Rodando o projeto
Abrir a pasta do projeto, abrir um terminal (cmd) e digitar a seguinte lista de comandos para instalar todas as dependências necessárias: 
- `npm install` 
- `npm audit fix`
- `composer update`

Faça uma cópia do arquivo `.env.example` e renomeie para `.env` e altere as informações do banco de dados,
onde<br>
`DB_CONNECTION=mysql` (SGBDR, no qual estamos usando o mysql)<br>
`DB_HOST=127.0.0.1` (ip do banco de dados)<br>
`DB_PORT=3306` (porta do banco de dados)<br>
`DB_DATABASE=covidinfo` (nome do schema do banco de dados)<br>
`DB_USERNAME=root` (nome de usuário do banco de dados)<br>
`DB_PASSWORD=` (deixe em branco caso não possua senha)<br>
após instalar todas as dependências e configurar o arquivo .env, utilize o comando `php artisan serve` para subir o servidor da aplicação.
