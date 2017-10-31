<?php

include 'class/Database.php';

class Users extends Database
{
	

}

$obj = new Users();
$b=$obj->where(['username'=>'tonmoy','id'=>'24']);
$a=$obj->get('users');
print_r($a);
