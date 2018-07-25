<?php

class test
{
}

function fun_empty()
{
    // 问题：在empty函数中 什么数据返回true 什么返回false?
    
    var_dump(empty('')); // bool(true)
    var_dump(empty(' ')); // bool(false)
    var_dump(empty('  ')); // bool(false)
    
    var_dump(empty(0)); // bool(true)
    var_dump(empty('0')); // bool(true)
    
    var_dump(empty(array())); // bool(true)
    
    var_dump(empty(FALSE)); // bool(true)
    var_dump(empty('FALSE')); // bool(false)
    var_dump(empty(NULL)); // bool(true)
    
    $var;
    //var_dump($var);
    var_dump(empty($var)); // bool(true)
    
    $o = new test(); // bool(false)
    var_dump(empty($o));
    
    echo "\n";
    // 总结：未定义的变量【未赋值】，空数组，NULL，0，'',没有任何属性的对象 都被认为是空
    // 未定义的变量 本质上就是NULL
}
fun_empty();
echo "\n";

function function_name14()
{
    // 问题：如何定义一个常量并判断一个常量是否定义？
    define("STR", "123");
    // 引用常量 123
    echo STR;
    echo "\n";
    
    // 注意：一个常量被定义之后不可以在定义它
    // define函数定义成功之后 返回1
    echo define("STR1", "456"); // 输出1 Notice: Constant STR already defined in D:\wamp\www\Demo2\defined.php on line 7
    echo "\n";
    
    // 判断一个常量是否被定义
    if(defined("STR"))
    {
        echo "YES";
    }
    else
    {
        echo "NO";
    }
    echo "\n";
    
    if(! defined("STR"))
    {
        echo "NO";
    }
    else
    {
        echo "YES";
    }
    echo "\n";
    
    /**
     * defined() 函数检查某常量是否存在
     * 若常量存在，则返回 true，否则返回 false
     */
    
    define("GREETING", "Hello world!");
    // 返回值1 表示已经定义
    echo defined("GREETING");
}

function_name14();

function function_name15()
{
    // 问题：echo 和print的区别
    
    // 001.都可以打印变量
    $str = "Hello";
    echo $str, "<br />"; // Hello
    print $str; // Hello
                
    // 002.都可以在字符串中引用变量
    echo '<hr>'; // 引用变量 ：$str
    echo '引用变量 ：$str'; // 注意：单引号中引用变量 变量不会解析的
    
    echo '<hr>'; // 引用变量 ：$str
    print '引用变量 ：$str'; // 引用变量 ：$str
    echo '<hr>';
    
    // 小知识：单双引号的区别
    echo '<hr>';
    echo "引用变量 ：$str"; // 引用变量 ：Hello
    
    echo '<hr>';
    print "引用变量 ：$str"; // 引用变量 ：Hello
    echo '<hr>';
    
    $txt1 = "Learn PHP";
    $txt2 = "W3School.com.cn";
    $cars = array(
        "Volvo",
        "BMW",
        "SAAB"
    );
    
    // 花括号中也可以应用变量
    print $txt1; // Learn PHP
    print "<br>";
    print "Study PHP at $txt2"; // Study PHP at W3School.com.cn
    print "<br>";
    print "My car is a {$cars[0]}"; // My car is a Volvo
                                    
    // echo可以打印多个变量 一次性
    echo "\n";
    echo $txt1, $txt2, $cars; // echo 不能打印数组
    echo "\n";
    print $cars; // print 不能打印数组
}
echo "\n";
function_name15();

function function_name17($param)
{
    // 问题：isset和empty的区别？
    $abb = 01;
    if(isset($abb))
    {
        echo '设置了';
    }
    else
    {
        echo '未设置';
    }
    
    if(empty($abb))
    {
        echo '空值';
    }
    else
    {
        echo '不为空';
    }
}

function function_var_dump()
{
    // 问题：将var_dump的结果保存，比如说存进日志，亦或是传给变量后留作他用
    ob_start();
    var_dump($_SERVER);
    $result = ob_get_clean();
    
    // 这里的$result，你得到了之后可以存到文件中，看看是不是很赞
    
    if(! $fhandle = fopen("log.txt", "a+"))
    {
        print "error";
        exit();
    }
    fwrite($fhandle, $php);
    fclose($fhandle);
}

function writeLog($variables, $show = false, $logPath = "/tmp/log.txt")
{
    ob_start();
    if(is_array($variables) && true == $show)
    {
        print_r($variables);
    }
    else
    {
        var_dump($variables);
    }
    $data = ob_get_clean();
    @file_put_contents($logPath, $data);
}

function function_name()
{
    // 问题：print_r()函数和var_dump()函数区别
    $arr_test = array(
        1,
        2,
        3
    );
    print_r($arr_test);
    // 运行该例子输出：
    // Array
    // (
    // [0] => 1
    // [1] => 2
    // [2] => 3
    // )
    
    // var_dump()函数
    $arr_test = array(
        1,
        2,
        3
    );
    var_dump($arr_test);
    // 运行该例子输出：
    // array(3)
    // {
    // [0]=>
    // int(1)
    // [1]=>
    // int(2)
    // [2]=>
    // int(3)
    // }
    // var_dump()函数同print_r()函数用法一样。不过var_dump()函数功能比print_r()更强大，可以同时打印多个变量且给出变量的类型信息。
}





