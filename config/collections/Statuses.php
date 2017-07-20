<?php

use Norm\Schema\String;
use Norm\Schema\Integer;

return array(
	'observers' => array(
        'App\\Observer\\Code'
    ),
    'schema' => array(
        'code' => Integer::create('code')->filter('trim')->set('list-column', false)->set('hidden', true),
        'name' => String::create('name')->filter('trim|required')->set('list-column', true),
        'color' => String::create('color')->filter('trim')->set('list-column', true)
    ),
);