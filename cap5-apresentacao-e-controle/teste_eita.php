<?php

// App loader
require_once 'Lib/Livro/Core/AppLoader.php';
$al = new Livro\Core\AppLoader;
$al->addDirectory('App/Model');
$al->register();

$pagina = new Eita("teste");
