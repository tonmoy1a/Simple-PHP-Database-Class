<?php

include 'class/Database.php';

class Example extends Database
{
    

}

$obj = new Example;
echo '<pre>';
$b=$obj->limit(0, 5)->getAll('vw_sell_with_price');
$b=$obj->insert('as', array('name' => 'values', ));
//print_r($b);
