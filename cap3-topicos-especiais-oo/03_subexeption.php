<?php

require_once 'classes/CSVParser.php';

// definição das subclasses de erro

$csv = new CSVParser('docs/clientes.csv', ';');
$csv->parse();

while ($row = $csv->fetch()) {
//    var_dump($row);
    echo $row['Cliente'] . " - " . $row['Cidade'] . " - " . $row['Sexo'] . "<br>\n";
} 