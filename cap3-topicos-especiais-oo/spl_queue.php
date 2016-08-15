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

$ingredientes = new SplQueue(); 

// acrescentando na fila 
$ingredientes->enqueue('Peixe'); 
$ingredientes->enqueue('Sal'); 
$ingredientes->enqueue('Limão'); 

foreach ($ingredientes as $item) { 
    print 'Item: ' . $item . '<br>' . PHP_EOL; 
} 

print '<br>' . PHP_EOL; 

// removendo da fila 
print $ingredientes->dequeue() . '<br>' . PHP_EOL; 
print 'Count: ' . $ingredientes->count() . '<br><br>' . PHP_EOL; 

print $ingredientes->dequeue() . '<br>' . PHP_EOL; 
print 'Count: ' . $ingredientes->count() . '<br><br>' . PHP_EOL; 

print $ingredientes->dequeue() . '<br>' . PHP_EOL; 
print 'Count: ' . $ingredientes->count() . '<br><br>' . PHP_EOL;