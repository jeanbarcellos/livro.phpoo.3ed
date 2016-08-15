<?php

/**
 * spl_autoload_register
 * A função é responsável por definir um algoritmo de carga de classes, ou seja,
 * ela indica para o PHP como as classes serão carregadasa partir de seu nome.
 */
spl_autoload_register(function ($class) {
    require_once(str_replace('\\', '/', $class . '.php'));
});

use Library\Widgets\Field;

var_dump(new Field);
