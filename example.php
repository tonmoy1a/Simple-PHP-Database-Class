<?php

include 'class/Database.php';

class Example extends Database
{
	

}

$obj = new Example;
echo '<pre>';
$b=$obj->select(['id','title'])->where(['id'=>21])->order_by('id', 'DESC')->limit(0,5)->getAll('courses');

print_r($b);