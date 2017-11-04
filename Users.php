<?php

include 'class/Database.php';

class Users extends Database
{
	

}

$obj = new Users();
echo '<pre>';
$b=$obj->order_by('id', 'DESC')->limit(3,5)->getAll('courses');

print_r($b);