<?php 

/**
 * RecursiveIteratorIterator
 * é um objeto que implementa a recursão.
 * 
 * RecursiveDirectoryIterator
 * 
 * Recursive<Array>Iterator
 * Recursive<Tree>Iterator
 * 
 * 
 */

$path = 'tmp'; 

foreach (new RecursiveIteratorIterator( 
    new RecursiveDirectoryIterator($path, 
        RecursiveDirectoryIterator::SKIP_DOTS)) as $item) 
{ 
    print( (string) $item . '<br>' . PHP_EOL); 
}