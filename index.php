<?php 

$path = ".";
        
$diretorio = dir($path);

echo "<ul>";

while ($arquivo = $diretorio->read()) {
    
    if($arquivo != "." ) {       
        echo "<li><a href='" . $arquivo . "'>" . $arquivo . "</a></li>";
    }
} 

echo "<ul>";

$diretorio->close();

