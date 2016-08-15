<?php

/**
 * SplFileInfo
 * 
 * oferece funcionalidades para obtenção de informações 
 * a respeito de determinado arquivo
 * 
 */
$file = new SplFileInfo('spl_file_info.php');

print 'Nome: ' . $file->getFileName() . '<br>' . PHP_EOL; // Nome do arquivo
print 'Extensão: ' . $file->getExtension() . '<br>' . PHP_EOL; // Extensão do arquiv
print 'Tamanho: ' . $file->getSize() . '<br>' . PHP_EOL; // Tamanho do arquivo
print 'Caminho: ' . $file->getRealPath() . '<br>' . PHP_EOL; // Caminho completo do arquivo
print 'Tipo: ' . $file->getType() . '<br>' . PHP_EOL; // Tipo de arquivo
print 'Gravação: ' . $file->isWritable() . '<br>' . PHP_EOL; // Pode ser escrito?
