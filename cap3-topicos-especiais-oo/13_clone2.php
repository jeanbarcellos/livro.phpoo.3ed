<?php

class Titulo {

    public $codigo, $dt_vencimento, $valor, $juros, $multa;

    public function __clone() {
        $this->codigo = NULL;
    }

}

$titulo = new Titulo;
$titulo->codigo = 1;
$titulo->dt_vencimento = '2015-05-20';
$titulo->valor = 12345;
$titulo->juros = 0.1;
$titulo->multa = 2;

$titulo2 = clone $titulo;
$titulo2->valor = 78910;

//var_dump($titulo->valor);
//var_dump($titulo2->valor);
//
//var_dump($titulo->codigo);
//var_dump($titulo2->codigo);

var_dump($titulo);

var_dump($titulo2);
