<?php

namespace Livro\Database;

/**
 * Permite definição de critérios
 * 
 * @author Pablo Dall'Oglio
 */
class Criteria extends Expression {

    /**
     * Armazena a lista de expressões
     * @var type 
     */
    private $expressions;

    /**
     * Armazena a lista de operadores
     * 
     * @var type 
     */
    private $operators;

    /**
     * Propriedades do critério
     * 
     * @var type 
     */
    private $properties;

    /**
     * Método Construtor
     */
    function __construct() {
        $this->expressions = array();
        $this->operators = array();
    }

    /**
     * Adiciona uma expressão ao critério
     * 
     * @param $expression Expressão (objeto Expression)
     * @param $operator Operador lógico de comparação
     */
    public function add(Expression $expression, $operator = self::AND_OPERATOR) {
        // na primeira vez, não precisamos de operador lógico para concatenar
        if (empty($this->expressions)) {
            $operator = NULL;
        }

        // agrega o resultado da expressão à lista de expressões
        $this->expressions[] = $expression;
        $this->operators[] = $operator;
    }

    /**
     * Retorna a expressão final
     */
    public function dump() {
        // concatena a lista de expressões
        if (is_array($this->expressions)) {
            if (count($this->expressions) > 0) {
                $result = '';
                foreach ($this->expressions as $i => $expression) {
                    $operator = $this->operators[$i];
                    // concatena o operador com a respectiva expressão
                    $result .= $operator . $expression->dump() . ' ';
                }
                $result = trim($result);
                return "({$result})";
            }
        }
    }

    /**
     * Define o valor de uma propriedade
     * 
     * @param $property Propriedade
     * @param $value Valor
     */
    public function setProperty($property, $value) {
        if (isset($value)) {
            $this->properties[$property] = $value;
        } else {
            $this->properties[$property] = NULL;
        }
    }

    /**
     * Retorna o valor de uma propriedade
     * 
     * @param $property Propriedade
     */
    public function getProperty($property) {
        if (isset($this->properties[$property])) {
            return $this->properties[$property];
        }
    }

}
