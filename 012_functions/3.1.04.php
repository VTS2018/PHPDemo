<?php
/*
 * 学习要点
 * 函数参数
 * 1.无惨函数
 * 2.有参函数
 * 3.有参默认值函数
 */
$line = array(
    1,
    2,
    3,
    4,
    5,
    6,
    7,
    8,
    9
);

// 定义一个无参函数.
function noVar()
{
    // 在函数里使用全局化变量$line
    global $line;
    
    // 遍历全局变量数组
    echo "无参函数遍历外部数组:<br />";
    foreach ($line as $value)
    {
        echo "$value ";
    }
    echo "<br />";
}
noVar();
echo "<hr />";

// 定义一个有参函数
function userVar($var)
{
    // 遍历参数
    echo "有参函数遍历函数参数:<br>";
    foreach ($var as $value)
    {
        echo "$value-";
    }
    echo "<br>";
}
// 使用有参函数处理数组
userVar($line);
echo "<hr />";

// 定义一个有默认参数的有参函数
function haveVar($var1 = 10, $var2 = array("a","b","c","d"))
{
    // 在函数内显示参数1
    echo "$var1<br>";
    
    // 在函数内遍历参数2
    foreach ($var2 as $value)
    {
        echo "$value-";
    }
    echo "<br>";
}
echo "真接调用有默认值的有参函数:<br>";
haveVar();
echo "<hr />";

echo "为有默认值的有参函数添加新参数<br>";
$v = "我是字符串";
haveVar($v, $line);

