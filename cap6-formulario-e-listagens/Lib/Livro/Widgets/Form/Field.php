<?php

namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

/**
 * Representa um campo de um formulário
 * @author Pablo Dall'Oglio
 */
abstract class Field implements FormElementInterface {

    protected $name;
    protected $size;
    protected $value;
    protected $editable;
    protected $tag;
    protected $formLabel;

    /**
     * Instancia um campo do formulario
     * 
     * @param $name Nome do campo
     */
    public function __construct($name) {
        // define algumas características iniciais
        self::setEditable(true);
        self::setName($name);
        self::setSize(200);

        // cria uma tag HTML do tipo <input>
        $this->tag = new Element('input');
        $this->tag->class = 'field';    // classe CSS
    }

    /**
     * Intercepta a atribuição de propriedades
     * 
     * @param $name     Nome da propriedade
     * @param $value    Valor da propriedade
     */
    public function __set($name, $value) {
        // Somente valores escalares
        if (is_scalar($value)) {
            // Armazena o valor da propriedade
            $this->setProperty($name, $value);
        }
    }

    /**
     * Retorna o valor da propriedade
     * 
     * @param $name Nome da propriedade
     */
    public function __get($name) {
        return $this->getProperty($name);
    }

    /**
     * Define o nome do widget
     * 
     * @param $name Nome do widget
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Retorna o nome do widget
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Define o label do widget
     * 
     * @param $label Widget label
     */
    public function setLabel($label) {
        $this->formLabel = $label;
    }

    /**
     * Retorna o label do widget
     */
    public function getLabel() {
        return $this->formLabel;
    }

    /**
     * Define o valor de um campo
     * 
     * @param $value Valor do campo
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * Retorna o valor de um campo
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Define se o campo poderá ser editado
     * 
     * @param $editable TRUE ou FALSE
     */
    public function setEditable($editable) {
        $this->editable = $editable;
    }

    /**
     * Retorna o valor da propriedade $editable
     */
    public function getEditable() {
        return $this->editable;
    }

    /**
     * Define uma propriedade para o campo
     * 
     * @param $name Nome da propriedade
     * @param $value Valor da propriedade
     */
    public function setProperty($name, $value) {
        // define uma propriedade de $this->tag
        $this->tag->$name = $value;
    }

    /**
     * Retorna uma propriedade do campo
     */
    public function getProperty($name) {
        return $this->tag->$name;
    }

    /**
     * Define a largura do widget
     * @param $width Largura em pixels
     * @param $height Altura em pixels (usada em TText)
     */
    public function setSize($width, $height = NULL) {
        $this->size = $width;
    }

}
