<?php
/**
 * DirectoryIterator();
 * 
 * Permite percorrer um diretório, retornando cada um dos itens.
 * Cada item retornado também é uma instância de DirectoryInterator,
 * que por sua vez estende a classe SplFileInfo()
 */

$inter = new DirectoryIterator('tmp');

foreach ($inter as $file) {
    print (string) $file . '<br>' . PHP_EOL;
    print 'Nome: ' . $file->getFileName() . '<br>' . PHP_EOL;
    print 'Extensão: ' . $file->getExtension() . '<br>' . PHP_EOL;
    print 'Tamanho: ' . $file->getSize() . '<br>' . PHP_EOL;
    print '<br>' . PHP_EOL;
}