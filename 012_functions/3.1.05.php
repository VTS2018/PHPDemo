<?php

/*
 * 函数返回值
 * 1.和.net不同之处：可以同时返回两种不同的类型
 * 2.使用return关键字来返回值
 * 3.字符串对象的scalar属性
 */

// 定义一个遍历数组的函数
function listArray($arr)
{
    foreach ($arr as $val)
    {
        echo $val;
    }
}

function returnString()
{
    return 'Hello World!';
}

// 调用函数
echo returnString();
echo '<br />';

// 返回一个对象
function returnObject()
{
    $obj = (object) "字符串";
    return $obj;
}

// 返回值是一个对象
$newObj = returnObject();
print_r($newObj);
echo $newObj->scalar;
