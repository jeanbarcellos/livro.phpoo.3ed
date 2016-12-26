<?php

namespace Livro\Widgets\Base;

/**
 * Representa uma imagem
 * @author Pablo Dall'Oglio
 */
class Image extends Element {

    /**
     * Localização da imagem
     * 
     * @var type 
     */
    private $source;

    /**
     * Instancia uma imagem
     * 
     * @param $source Localização da imagem
     */
    public function __construct($source) {
        parent::__construct('img');

        // atribui a localização da imagem
        $this->src = $source;
        $this->border = 0;
    }

}
