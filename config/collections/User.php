<?php

use Norm\Schema\String;
use Norm\Schema\Password;
use Norm\Schema\Reference;
use App\Schema\RoleArray;

$role = "";
if (isset($_SESSION['user']['role'])) {
    $role = Reference::create('role')->to('Role', 'name')->format('plain', $_SESSION['user']['role'][0]);
}

return array(
    'schema' => array(
        'first_name' => String::create('first_name')->filter('trim|required')->set('list-column', true),
        'last_name' => String::create('last_name')->filter('trim')->set('list-column', true),
        'username' => String::create('username')->filter('trim|required|unique:User,username')->set('list-column', true),
        'normalized_username' => String::create('normalized_username')->filter('trim')->set('list-column', false)->set('hidden',true),
        'email' => String::create('email')->filter('trim|required|unique:User,email')->set('list-column', true),
        'password' => Password::create('password')->filter('trim|confirmed|salt'),
        'birthday' => String::create('birthday')->filter('trim'),
        'gender' => String::create('gender')->filter('trim'),
        'mobile_phone' => String::create('mobile_phone')->filter('trim'),
        'address' => String::create('address')->filter('trim'),
        'role'    => $role == 'Super Admin' ? RoleArray::create('role')->to('Role', '$id', 'name')->set('list-column', false) : RoleArray::create('role')->to('Role', '$id', 'name')->set('list-column', false)->set('hidden', true),
    ),
);