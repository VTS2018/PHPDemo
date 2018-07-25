<?php

function function_name98()
{
    // 问题：PHP中 对于未定义的变量 值是什么？
    // 值为null
    var_dump($new_price);
}

function_name98();

function function_name99()
{
    // 问题：PHP $_GET 是否可读可写值
    
    // http://localhost/Demo/global/index.php?main=index
    
    // 认识 $_GET 可读可写
    echo $_GET["main"]; // index
                        // 可写
    $_GET["main"] = "product_info";
    // 可读
    echo $_GET["main"]; // product_info
}

function_name99();

// ///////////////////////////////////////////
// 问题：一个对象是否能够转为字符串？转换之后是什么？
// 不能转换
class Man2
{

    var $name = '';

    var $age = 0;

    function __construct($var = '')
    {
        $this->name = $var;
    }

    // 为了兼容PHP4
    function Man2($name = 'Tom', $age = 18)
    {
        $this->name = $name;
        $this->age = $age;
    }

    function getName()
    {
        return $this->name;
    }
}
$tom = new Man2("Tom");

// echo ((string)$tom);
// Object of class Man2 could not be converted to string in D:\wamp\www\Demo\QA\02.php on line 55

function function_name5()
{
    // 问题：in_array函数内部的隐式转换
    
    // 由于字符串转int 会输出0
    $a = (int) 'abc';
    var_dump($a); // int(0)
    
    $c = array(
        0,
        1,
        2,
        3
    );
    
    // 如果你用字符串去检查的话 你会发现这个结果是TRUE 但是很明显abc 怎么会在数组c中呢？
    // 原因是 in_array 会将 abc做隐式的转换 【abc-隐式转换之后结果是0】
    if(in_array('abc', $c))
    {
        echo 'exist';
    }
    else
    {
        echo 'not exist';
    } // exist
      
    // 正确的做法是 加上类型的检查
    if(in_array('abc', $c, true))
    {
        echo 'exist';
    }
    else
    {
        echo 'not exist';
    } // not exist
}

function_name5();

