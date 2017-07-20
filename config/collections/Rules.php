<?php

use Norm\Schema\String;

return array(
    'schema' => array(
        'name' => String::create('name')->filter('trim|required')->set('list-column', true),
        'value' => String::create('value')->filter('trim|required')->set('list-column', true)
    ),
);