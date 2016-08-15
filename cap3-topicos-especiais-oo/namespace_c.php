<?php

require_once 'namespace_a.php';

use Application\Form;

var_dump(new Form); // object(Application\Form) 


var_dump(new Field); // Fatal error: Class 'Field'