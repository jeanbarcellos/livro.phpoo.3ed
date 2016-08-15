<?php

/**
 * ReflectionClass
 * 
 * Investiga uma determinada classe.
 * 
 */
require_once 'reflection_veiculo.php'; 

$rc = new ReflectionClass('Automovel'); 

var_dump($rc->getMethods());
var_dump($rc->getProperties());
var_dump($rc->getParentClass());