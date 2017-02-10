<?php

date_default_timezone_set('America/Sao_Paulo');

// Lib loader
require_once 'Lib/Livro/Core/ClassLoader.php';
$al = new Livro\Core\ClassLoader;
$al->addNamespace('Livro', 'Lib/Livro');
$al->register();

// App loader
require_once 'Lib/Livro/Core/AppLoader.php';
$al = new Livro\Core\AppLoader;
$al->addDirectory('App/Control');
$al->addDirectory('App/Model');
$al->register();

$template = file_get_contents('App/Templates/template.html');
$content = '';
$class = 'Home';

if ($_GET) {
    $class = $_GET['class'];
    if (class_exists($class)) {
        try {
            $pagina = new $class;

            ob_start(); // Inicia controle de output

            $pagina->show(); // Exibe a página

            $content = ob_get_contents(); // Lê o conteúdo gerado

            ob_end_clean(); // Finaliza o controle de output
        } catch (Exception $e) {
            $content = $e->getMessage() . '<br>' . $e->getTraceAsString();
        }
    }
}

$output = $content;
$output = str_replace('{content}', $content, $template);
$output = str_replace('{class}', $class, $output);

echo $output;
