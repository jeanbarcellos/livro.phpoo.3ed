<?php

class Titulo {

    public $codigo;
    public $dt_vencimento;
    public $valor;
    public $juros;
    public $multa;

}

$titulo = new Titulo;
$titulo->codigo = 1;
$titulo->dt_vencimento = '2015-05-20';
$titulo->valor = 12345;
$titulo->juros = 0.1;
$titulo->multa = 2;

$titulo2 = $titulo;
$titulo2->valor = 78910;

var_dump($titulo->valor);
var_dump($titulo2->valor);
