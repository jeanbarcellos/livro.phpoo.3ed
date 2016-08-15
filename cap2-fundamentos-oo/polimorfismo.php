<?php

require_once 'classes/Conta.php';
require_once 'classes/ContaPoupanca.php';
require_once 'classes/ContaCorrente.php';

// criação dos objetos 
$contas = array();
$contas[] = new ContaCorrente(6677, "CC.1234.56", 100, 500);
$contas[] = new ContaPoupanca(6678, "PP.1234.57", 100);

// percorre as contas 
foreach ($contas as $key => $conta) {

    echo "Conta: {$conta->getInfo()} <br>\n";
    echo "    Saldo atual: {$conta->getSaldo()} <br>\n";
    $conta->depositar(200);
    echo "    Depósito de: 200 <br>\n";
    echo "    Saldo atual: {$conta->getSaldo()} <br>\n";

    if ($conta->retirar(700)) {
        echo "    Retirada de: 700 <br>\n";
    } else {
        echo "    Retirada de: 700 (não permitida)<br>\n";
    }
    echo "    Saldo atual: {$conta->getSaldo()} <br>\n";
    echo "<br>";
} 