<?php
/**
 * Read() é a class responsavle por realizar consultas no banco de dados
 * com ela é possivel de uma forma simples realizar consultas complexas 
 * bastando entender o funcionaento desta.
 * 
 * Read() trás consigo alguns importantes metodos os quais listaremos a baixo.
 * 
 * ->ExeRead() #realisa uma consulta parametrizada no banco de dados 
 * ->FullRead() # Executa leitura no banco de dados via query
 * ->getResult() # Retorna o resultado da consulta
 * ->getRowCount() # retorna o numero de registro retornado pela consulta
 */
require './vendor/adaoreis/crud/vendor/autoload.php';
$loginDB = array("HOST"=>"localhost",'USER'=>"root", 'PASS'=>"", 'DBSA'=>"");
define("HOST","localhost"); //constane HOST
define('USER',"root");// usuario do banco de dados
define('PASS',"");// senha do banco de dados
define('DBSA',"");// base de dados a ser utilizada


$read = new AR\BD\Read(); //instancia de read
$read->ExeRead("aluno.aluno","",""); // executa a consulta no banco indicado
print_r($read->getResult());// imprime o resultado na tela 