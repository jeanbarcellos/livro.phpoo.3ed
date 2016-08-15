<?php

require_once 'namespace_a.php';
require_once 'namespace_b.php';

use Application\Form as Form;
use Application\Field as Field;

var_dump(new Form);  // object(Application\Form) 

var_dump(new Field); // object(Application\Field) 


use Components\Form as ComponentForm;

var_dump(new ComponentForm); // object(Components\Form) 


var_dump(new Components\Form); // object(Components\Form)

var_dump(new Application\Form); // object(Application\Form)
