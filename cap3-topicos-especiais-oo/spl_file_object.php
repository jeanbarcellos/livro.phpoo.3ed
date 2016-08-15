<?php

/**
 * SplFileObject
 * fornece uma maneira orientada a objetos para manipular arquivos.
 * 
 * SplFileObject extende a classe SplFileInfo
 * com isto, 
 * além de oferecer recursos de manipulação de arquivos 
 * ela também oferece recursos de obtenção de informações.
 */

$file = new SplFileObject('spl_file_object.php');
print 'Nome: ' . $file->getFileName() . '<br>' . PHP_EOL;
print 'Extensão: ' . $file->getExtension() . '<br>' . PHP_EOL;

$file2 = new SplFileObject("docs/novo.txt", "w");
$bytes = $file2->fwrite('Olá Mundo PHP' . PHP_EOL);
print 'Bytes escritos ' . $bytes . PHP_EOL;
