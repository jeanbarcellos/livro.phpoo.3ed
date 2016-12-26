<?php

namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

/**
 * Representa uma célula de uma tabela
 * 
 * @author Pablo Dall'Oglio
 */
class TableCell extends Element {

    /**
     * instancia uma nova célula
     * @param $value Conteúdo da célula
     */
    public function __construct($value) {
        parent::__construct('td');
        parent::add($value);
    }

}
