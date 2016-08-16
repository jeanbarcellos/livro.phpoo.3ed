<?php

/**
 * Classe TRepository
 * 
 * Provê os métodos necessários para manipular coleções de objetos.
 */
final class Repository {

    private $activeRecord; // classe manipulada pelo repositório

    /**
     * Instancia um Repositório de objetos
     * @param string $class Classe dos Objetos
     */
    function __construct($class) {
        $this->activeRecord = $class;
    }

    /**
     * Recupera um conjunto de objetos (collection) da base de dados
     * através de um critério de seleção, e instanciá-los em memória.
     * @param $criteria = objeto do tipo Criteria
     * @return Array Array de objetos
     */
    function load(Criteria $criteria) {
        
        // instancia a instrução de SELECT
        $sql = "SELECT * FROM " . constant($this->activeRecord . '::TABLENAME') . "";

        // obtém a cláusula WHERE do objeto Criteria.
        if ($criteria) {
            $expression = $criteria->dump();
            if ($expression) {
                $sql .= " WHERE " . $expression;
            }

            // obtém as propriedades do critério
            $order = $criteria->getProperty('order');
            $limit = $criteria->getProperty('limit');
            $offset = $criteria->getProperty('offset');

            // obtém a ordenação do SELECT
            if ($order) {
                $sql .= " ORDER BY " . $order;
            }
            // obtém o limite da consulta
            if ($limit) {
                $sql .= " LIMIT " . $limit;
            }
            if ($offset) {
                $sql .= ' OFFSET ' . $offset;
            }
        }
        $sql .= ";";

        // obtém transação ativa
        if ($conn = Transaction::get()) {
            
            // registra mensagem de log
            Transaction::log($sql); 
            
            // executa a consulta no banco de dados
            $result = $conn->query($sql);
            
            $results = array();

            if ($result) {
                // percorre os resultados da consulta, retornando um objeto
                while ($row = $result->fetchObject($this->activeRecord)) {
                    // armazena no array $results;
                    $results[] = $row;
                }
            }
            return $results;
        } else {
            // se não houver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    /**
     * Exclui um conjunto de objetos (collection) da base de dados
     * através de um critério de seleção.
     * @param $criteria = objeto do tipo Criteria
     */
    function delete(Criteria $criteria) {        
        // Monta instrução de DELETE
        $sql = "DELETE FROM " . constant($this->activeRecord . '::TABLENAME'); 
        
        // atribui o critério passado como parâmetro
        $expression = $criteria->dump();
        if ($expression) {
            $sql .= " WHERE " . $expression;
        }
        $sql .= ";";

        // obtém transação ativa
        if ($conn = Transaction::get()) {
            // registra mensagem de log
            Transaction::log($sql);
            
            // executa instrução de DELETE
            $result = $conn->exec($sql);
            
            return $result;
        } else {
            // se não tiver houver, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    /**
     * Retorna a quantidade de objetos da base de dados
     * que satisfazem um determinado critério de seleção.
     * @param $criteria = objeto do tipo Criteria
     */
    function count(Criteria $criteria) {
        
        // instancia instrução de SELECT
        $sql = "SELECT count(*) FROM " . constant($this->activeRecord . '::TABLENAME');
        
        $expression = $criteria->dump();
        if ($expression) {
            $sql .= ' WHERE ' . $expression;
        }
        $sql .= ";";

        // obtém transação ativa
        if ($conn = Transaction::get()) {
            // registra mensagem de log
            Transaction::log($sql);
            
            // executa instrução de SELECT
            $result = $conn->query($sql);
            if ($result) {
                $row = $result->fetch();
            }

            return $row[0]; // retorna o resultado
        } else {
            throw new Exception('Não há transação ativa!!');
        }
    }

}
