<?php
$list_box_contents = array();

$row = 0;
$col = 0;

for($row = 0; $row < 3; $row ++)
{
    $list_box_contents[$row][$col] = array(
        "row" => $row,
        "col" => $col
    );
    $col ++;
}

var_dump($list_box_contents);

print_r($list_box_contents);

$cars = array
(
    "10"=>array("id"=>1,"name"=>"Volvo","price"=>22.33),
    "20"=>array("id"=>1,"name"=>"BMW","price"=>22.33),
    "30"=>array("id"=>1,"name"=>"Saab","price"=>22.33),
    "40"=>array("id"=>1,"name"=>"Land Rover","price"=>22.33),
);

var_dump($cars);
