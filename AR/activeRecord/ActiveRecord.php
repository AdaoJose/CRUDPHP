<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActiveRecord
 *
 * @author Adão José PC->cidinha
 * adao.jose123.a.r@gmail.com
 * fb.com/adao.jose123.a
 */
namespace AR\ABS;
abstract class ActiveRecord {
    private $content;
    protected $table = NULL;
    protected $idField = NULL;
    protected $logTimestamp;
    
    public function __construct() {
        if (!is_bool($this->logTimestamp)) {
            $this->logTimestamp = TRUE;
        }
        if ($this->table == NULL) {
            $this->table = strtolower(get_class($this));
        }
        if ($this->idField == NULL) {
            $this->idField = 'id';
        };
    }
    /*
     * ****************************METODOS MAGICOS**************
     */
    
    public function __set($name, $value) {
        $this->content[$name] = $value;
    }
    public function __get($name) {
        return $this->content[$name];;
    }
    public function __isset($parameter){
        return isset($this->content[$parameter]);
    }
    public function __unset($parameter){
        if (isset($parameter)) {
            unset($this->content[$parameter]);
            return true;
        }
        return false;
    }
    private function __clone(){
        if (isset($this->content[$this->idField])) {
            unset($this->content[$this->idField]);
        }
    }
    /**
     * Retorna Content na forma de um array
     * @return Array
     */
    public function  toArray(){
        return $this->content;
    }
    /**
     * <b>fromArray</b> 
     * recebe o array com os dados com o qual se pretende trabalhar
     */
    public function fromArray(array $array){
        $this->content = $array;
    }
    /**
     * <b>toJson</b>
     * retorna content no formato json
     */
    public function toJson(){
        return json_encode($this->content);
    }
    /**
     * <b>fromJson</b>
     * Recebe JSON de dados com o qual se deve trabalhar
     */
    public function fromJson(string $json){
        $this->content = json_decode($json);
    }
    
    /**
     *<b>Eatch</b>
     * itera o conteudo ldo content
     * @param function $calbeck recebe o parametro array contendo cada um dos values individuais
     * 
     * $OBJ->eatch(function($array){
     *  echo $array['id'];
     * }) 
     */
    public function eatch($calback){
        foreach ($this->toArray() as $value) {
            
            call_user_func_array($calback, $value);
            
        }
        
    }


    private function format($value){
        if (is_string($value) && !empty($value)) {
            return "'" . addslashes($value) . "'";
        } else if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        } else if ($value !== '') {
            return $value;
        } else {
            return "NULL";
        }
    }
    /*
     * <b>convertContent<b>
     * Responsavkel por tratar content antes de enviar
     */
    private function convertContent(){
        $newContent = array();
        foreach ($this->content as $key => $value) {
            if (is_scalar($value)) {
                $newContent[$key] = $this->format($value);
            }
        }
        return $newContent;
    }
    public function save(){
        $newContent = $this->convertContent();

        if (isset($this->content[$this->idField])) {
            $sets = array();
            foreach ($newContent as $key => $value) {
                if ($key === $this->idField)
                    continue;
                $sets[] = "{$key} = {$value}";
            }
            $update = new \AR\BD\Update();
            $update->ExeUpdate($this->table, $this->toArray(), " WHERE {$this->idField} = {$this->content[$this->idField]}","");
        } else {
            $insert = new \AR\BD\Create();
            $insert->ExeCreate($this->table, $this->toArray());
        }
    }
    /*
     * Faz a pesquisa baseado no id 
     * passado por parametro 
     * @return Objeto contendo a instancia do da classe que o chamou 
     * Ex. $cliente = Tb_clientes::finde(1)
     * retornará a instancia da classe Tb_clientes com todos os valores que foram recuperados preenchidos
     */
    public static function find($parameter){
        $class = get_called_class();
        $instancia =  (new $class());//guarda a instanca da classe
        $idField = ($instancia)->idField;
        $table = ($instancia)->table;
        
        $tableFromRead = (is_null($table) ? strtolower($class) : $table);
        $Termos = ' WHERE ' . (is_null($idField) ? 'id' : $idField);
        $Termos.= " = {$parameter} ;";
        $select = new \AR\BD\Read();
        $select->ExeRead($tableFromRead, $Termos);


        if ($select->getRowCount() > 0){
            $res  = ($select->getResult()[0]);//adicionando resultado no content
            
            foreach ($res as $key => $value) {
              $instancia->$key = $value;  
            }   
        }

        return $instancia; //Retorna instancia da classe que extende ActiveRecord
        
    }
    /*
     * retorna todos os registros que for compativel com parametros
     * @param filter adiciona condição para retorno
     * Ex. nome=adao
     * 
     * @param $limit quantidade de registro a retornar
     * 
     * @param $offset apartir de qual indice deve comessar 
     * Ex. $offset=12 retornará todos os registros apartir do indice 12 sendo 13,14,15,.....
     */
    public static function all($filter = '', $limit = 0, $offset = 0){
        $class = get_called_class();
        $instancia =  (new $class());//guarda a instanca da classe
        $table = ($instancia)->table;
        
        $tableFromRead = (is_null($table) ? strtolower($class) : $table);
        $Termos = ($filter !== '') ? " WHERE {$filter}" : "";
        $Termos .= ($limit > 0) ? " LIMIT {$limit}" : "";
        $Termos .= ($offset > 0) ? " OFFSET {$offset}" : "";
        $read = new \AR\BD\Read();
        $read->ExeRead($tableFromRead, $Termos);
        
        if ($read->getRowCount() > 0){
            $res  = ($read->getResult());//adicionando resultado no content
            
            foreach ($res as $key => $value) {
                //echo "<br>{$key}";
                $newObj[$key] = $instancia;
                //foreach ($newObj as $chave => $val) {
                    //$newObj[$key]->$chave = $val;
                //}
              $instancia->$key = $value;  
            }   
        }
        return $instancia;  
        
    }
    /*
     * Retorna primeiro registro que corresponda ao filtro
     * @param $filter filtro
     * Ex. $filter = "nome='adao'"
     * Retornará todos os registros em que a tupla nome seja igual adao
     */
    public static function findFisrt(string $filter = ''){
        return self::all($filter, 1);
    }
    /*
     * <b>delete</b>
     * Deleta registro recuperado do banco
     * Ex. $teste = ClassName::find(1);
     * $teste->delete();
     * assim estaria deletando o registro de id=1
     */
    public function delete(){
        if (isset($this->content[$this->idField])) {

            $sql = "DELETE FROM {$this->table} WHERE {$this->idField} = {$this->content[$this->idField]};";
            $delet = new \AR\BD\Delete();
            $delet->ExeDelete($this->table, "WHERE {$this->idField} = {$this->content[$this->idField]}", "");
            
        }
    }
}
