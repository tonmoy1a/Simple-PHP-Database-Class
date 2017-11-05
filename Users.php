<?php

include 'class/Database.php';

class Users extends Database
{
	

}

$obj = new Users();
echo '<pre>';
$b=$obj->select(['id','title'])->where(['id'=>21])->order_by('id', 'DESC')->limit(0,5)->getAll('courses');

print_r($b);