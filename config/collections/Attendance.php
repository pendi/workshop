<?php

use Norm\Schema\String;
use Norm\Schema\Text;
use Norm\Schema\Reference;

return array(
    'schema' => array(
        'event' => Reference::create('event')->to('Event', 'title')->set('list-column', false),
        'user' => Reference::create('user')->to('User', 'first_name')->set('list-column', false),
        'time' => String::create('time')->filter('trim')->set('list-column', false),
        'status' => Reference::create('status')->to('Statuses', 'name')->set('list-column', false),
        'category' => Reference::create('category')->to('Category', 'name')->set('list-column', false),
        'description' => Text::create('description')->filter('trim')->set('list-column', false),
    ),
);