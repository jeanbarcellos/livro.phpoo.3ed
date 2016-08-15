<?php 
/**
 * Manipulando filas
 * 
 * SplStack() - pilha
 * SplQueue() - Fila
 * 
 * enqueue(mixed valor) - Adiciona valor a Fila
 * dequeue() - Remove da fila
 */

$ingredientes = new SplStack(); 

// acrescentando na pilha 
$ingredientes->push('Peixe'); 
$ingredientes->push('Sal'); 
$ingredientes->push('Limão'); 

foreach ($ingredientes as $item) { 
    print 'Item: ' . $item . '<br>' . PHP_EOL; 
} 

print '<br>' . PHP_EOL; 

// removendo da pilha 
print $ingredientes->pop() . '<br>' . PHP_EOL; 
print 'Count: ' . $ingredientes->count() . '<br><br>' . PHP_EOL; 

print $ingredientes->pop() . '<br>' . PHP_EOL; 
print 'Count: ' . $ingredientes->count() . '<br><br>' . PHP_EOL; 

print $ingredientes->pop() . '<br>' . PHP_EOL; 
print 'Count: ' . $ingredientes->count() . '<br>' . PHP_EOL;