<?php

require_once 'namespace_a1.php';
require_once 'namespace_b1.php';
require_once 'namespace_c1.php';

use Library\Widgets\Field; #a1

use Library\Widgets\Form; #c1

use Library\Container\Table; #b1

var_dump(new Field); // object(Library\Widgets\Field) 

var_dump(new Form);  // object(Library\Widgets\Form) 

var_dump(new Table); // object(Library\Container\Table) 

$f = new Form;
$f->show();
