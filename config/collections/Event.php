<?php

use Norm\Schema\String;
use Norm\Schema\Date;
use Norm\Schema\Reference;

return array(
    'schema' => array(
        'name' => String::create('name')->filter('trim|required')->set('list-column', true),
        'title' => String::create('title')->filter('trim|required')->set('list-column', true),
        'date' => Date::create('date')->filter('trim|required')->set('list-column', true),
        'category' => Reference::create('category')->to('Category', '$id', 'name')->set('list-column', true)->set('hidden', true),
        'status' => Reference::create('status')->to('Statuses', 'code', 'name')->set('list-column', true)->set('hidden', true)
    ),
);