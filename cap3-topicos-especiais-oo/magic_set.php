<?php

class Titulo {

    private $dt_vencimento;
    private $valor;
    private $juros;
    private $multa;

    public function __set($propriedade, $valor) {
        print "Tentou gravar $propriedade = $valor. Use setValor()<br>\n";
    }

    public function setValor($valor) {
        if (is_numeric($valor)) {
            $this->valor = $valor;
        }
    }

}

$titulo = new Titulo;
$titulo->valor = 12345;
