<?php

function fun_php_host()
{
    // 问题:HTTP_HOST和SERVER_NAME之间的区别，在具体使用中使用哪个？
    
    // 它的值可能是
    // 关于PHPhost
    // localhost
    // 192.168.0.103
    echo $_SERVER["HTTP_HOST"];
    echo "<br />";
    echo $_SERVER["SERVER_NAME"];
    echo "<br />";
    echo $_SERVER['SERVER_PORT'];
}

echo "<br />";
fun_php_host();
echo "<br />";

function fun_parse_url()
{
    // 问题：函数 parse_url 用于解析一个URL地址
    $test = parse_url("http://localhost/index.php?name=tank&sex=1#top");
    print_r($test);
    
    $url = "http://localhost/index.php?name=tank&sex=1#top";
    
    echo "scheme：" . parse_url($url, PHP_URL_SCHEME) . "<br/>";
    echo "host：" . parse_url($url, PHP_URL_HOST) . "<br/>";
    echo "path：" . parse_url($url, PHP_URL_PATH) . "<br/>";
    echo "query：" . parse_url($url, PHP_URL_QUERY) . "<br/>";
    echo "fragment：" . parse_url($url, PHP_URL_FRAGMENT) . "<br/>";
}

fun_parse_url();

function get_current_url()
{
    // 问题：在PHP中对URL进行解析,获取URL的各个部分 该如何做呢？
    
    // 测试网址: http://localhost/blog/testurl.php?id=5
    // 测试网址: http://192.168.0.103/Demo/index.php?id=5
    // 获取协议
    
    // 获取域名或主机地址 #localhost #192.168.0.103
    echo $_SERVER['HTTP_HOST'] . "<br>";
    
    // 获取网页地址 #/blog/testurl.php #/Demo/index.php
    echo $_SERVER['PHP_SELF'] . "<br>";
    
    // 获取网址参数 #id=5 #id=5
    echo $_SERVER["QUERY_STRING"] . "<br>";
    
    // 获取用户代理 表示前一个地址
    // echo $_SERVER['HTTP_REFERER']."<br>";
    
    // 获取完整的url
    echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    echo "<br>";
    echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
    echo "<br>";
    
    // http://localhost/blog/testurl.php?id=5
    // 包含端口号的完整url
    echo 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    echo "<br>";
    
    // http://localhost:80/blog/testurl.php?id=5
    // 只取路径
    $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
    
    echo dirname($url);
    // http://localhost/blog
}

echo "<hr >";
get_current_url();
echo "<hr >";

function function_name_if()
{
    // 问题：研究测试if语句的赋值判断
    $arr = array();
    
    // 下面两个变量模仿 方法的返回
    $arra = TRUE;
    $arrb = array(
        1,
        2
    );
    $arrc = FALSE;
    
    // 当方法返回有效缓存ok
    // if($arr = $arrb)
    // {
    // echo '返回有效缓存';
    // }
    // else
    // {
    // echo '无缓存';
    // }
    
    // 如果我使用！操作符号呢？是否正常呢 答案是 OK
    // if(! ($arr = $arrb))
    // {
    // echo '执行查询 并存入到缓存中';
    // }
    // else
    // {
    // echo '返回有效缓存';
    // }
    
    // 如果方法返回false 表示没有缓存 是否还有效呢？答案是可以的
    if(! ($arr = $arrc))
    {
        echo '执行查询 并存入到缓存中';
    }
    else
    {
        echo '返回有效缓存';
    }
    
    // 总结：PHP中可以在if语句中 使用赋值语句作为判断的条件，也可以使用！运算符号对赋值语句运算
    // 总结：赋值语句的if判断 分为两步执行 先赋值然后在对 “被赋值的变量” 进行逻辑运算
    // 问题：在PHP对数据进行逻辑运算是个什么情况？哪些数据运算后为真，哪些为假？
}
echo "\n";

function_name_if();
echo "\n";

function function_name2()
{
    // 问题：函数nl2br是做什么用的？
    $str = "a
b
e
f
c";
    // nl2br 在字符串中的新行（\n）之前插入换行符
    echo nl2br($str);
    echo "\n";
}
echo "\n";
function_name2();
echo "\n";
