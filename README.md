<h1>CRUDPHP</h1>

 Este projeto visa colaborar com a comunidade trazendo assim mais uma ferramenta para realização da persistência com o banco de dados.

A realização de conexão com o banco de dados não é uma tarefa difícil mais que devido à quantidade de vez que se a realize em um projeto pode acabar se tornando cansativo e pode até acabar poluindo o seu código.

Este projeto trabalha diretamente na camada de modelo de sua aplicação podendo ser utilizada para todas as conexões com o banco de dados, ou melhor, para a abstração do banco de dados de sua aplicação.

O projeto é construído utilizando o padrão psr-4 o que traz uma grande facilidade na hora de incluir em seu projeto.


  <h2>Instalação</h2>
  A inclusão do CRUDPHP é realizado com auxílio do composer executando o seguinte comando no console na pasta do projeto
  <code>
    composer require adaoreis/crud
  </code>
    
<h3>Read</h3>

<h4>Realizando leitura no banco de dados</h4>

  Read() é a class responsável por realizar consultas no banco de dados com ela é possível de uma forma simples realizar consultas complexas bastando intender o funcionamento desta. 
  Read() traz consigo alguns importantes métodos os quais listaremos a baixo.
   <p><code>->ExeRead()</code> realiza uma consulta parametrizada no banco de dados. </p>
   <p><code>->FullRead()</code>  Executa leitura no banco de dados via query</p>
   <p><code>->getResult()</code>  Retorna o resultado da consulta</p>
   <p><code>->getRowCount()</code>  retorna o numero de registro retornado pela consulta</p>
    

  <h4>Definido credencial de acesso ao banco</h4>
    Para que a conexão com o banco de dados seja realizado de forma correta.
    será preciso fornecer as seguintes constantes com os respectivos valores das
    informações de acesso ao BD que se almeja utilizar
  
  ```php
  define("HOST","localhost"); //constane HOST
  define('USER',"root");// usuario do banco de dados
  define('PASS',"");// senha do banco de dados
  define('DBSA',"");// base de dados a ser utilizada
  ```
  

  
  ```php
  require '/vendor/autoload.php'; //carregando autoload do composer
  $read = new AR\BD\Read(); //instancia de read
  $read->ExeRead("aluno.aluno","",""); // executa a consulta no banco indicado
  print_r($read->getResult());// imprime o resultado na tela
  ```

    

