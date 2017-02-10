<?php

// Lib loader
require_once 'Lib/Livro/Core/ClassLoader.php';
$cl = new Livro\Core\ClassLoader;
$cl->addNamespace('Livro', 'Lib/Livro');
$cl->register();

// App loader
require_once 'Lib/Livro/Core/AppLoader.php';
$al = new Livro\Core\AppLoader;
$al->addDirectory('App/Control');
$al->addDirectory('App/Model');
$al->register();

$pagina = new PessoaControl;
$pagina->show($_GET);
