<?php
/*
 * 学习要点
 * 变量函数 把函数当做一个变量
 * 1.和.net不太一样【.net中其实也有】
 * 2.将一个函数名赋值给一个变量【和委托有点像】
 * 3.function_exists：判断函数是否存在
 * 4.在使用函数变量之前，一定要判断函数是否存在，否则将会异常
 */
function showInt()
{
    echo 123;
}

function showString()
{
    echo "Hello";
}

showInt();
echo "\n";

showString();
echo "\n";

echo "<hr />";

// 将一个函数名赋值给一个变量
$action1 = "showInt";
$action1();
echo "<hr />";

$action1 = "showString";
$action1();
echo "<hr />";

// 在使用函数变量之前，一定要判断函数是否存在，否则将会异常
if(function_exists('showInt'))
{
    echo 123;
}