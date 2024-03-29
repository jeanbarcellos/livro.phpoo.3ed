<?php

// interpreta o documento XML
$xml = simplexml_load_file('xml/paises2.xml');

var_dump($xml);

// alteração de propriedades
$xml->moeda = 'Novo Real (NR$)';
$xml->geografia->clima = 'temperado';

// adiciona novo nodo
$xml->addChild('presidente', 'Chapolin Colorado');

// exibindo o novo XML
echo $xml->asXML();

// grava no arquivo paises2.xml
file_put_contents('xml/paises2.xml', $xml->asXML());
