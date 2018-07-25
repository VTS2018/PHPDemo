<?php

function function_name3()
{
    // 问题：怎么使用preg_match函数？
    $sys = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36';
    // 匹配失败了
    // Warning: preg_match(): Unknown modifier
    // 在正则模式中，用/做定界符，但正则中也含有/，因此会出现此错误，PHP误当后面的一个</span>中的斜杠是结束的定界符。
    preg_match('/^Chrome\/([d.]+)$/', $sys, $google);
    
    // $exp[0] = "Chrome";
    // $exp[1] = $google[1];
    
    // echo $exp[0] . ' ' . $exp[1];
    var_dump($google);
}

function_name3();
echo "\n";

function function_name4()
{
    // 从URL中获取主机名称
    preg_match('@^(?:http://)?([^/]+)@i', "http://www.runoob.com/index.html", $matches);
    var_dump($matches);
    
    $host = $matches[1];
    // 获取主机名称的后面两部分
    preg_match('/[^.]+\.[^.]+$/', $host, $matches);
    
    echo "domain name is: {$matches[0]}\n";
}

function_name4();
echo "\n";

function function_name()
{
    // 问题：在PHP中使用花括号作用是什么？单引号和双引号中一样吗？
    // 双引号解析变量 单引号不解析变量
    $var = 'Hello';
    echo "双引号：{$var}";
    echo '双引号：{$var}';
}

function_name();
echo "\n";

function function_name_die()
{
    // 问题：exit()和die()各自的作用是什么？有什么区别？
    /*
     * 学习要点
     * exit() 函数输出一条消息，并退出当前脚本
     * 如果 status 是字符串，则该函数会在退出前输出字符串
     * 如果 status 是整数，这个值会被用作退出状态。
     * 退出状态的值在 0 至 254 之间。退出状态 255 由 PHP 保留，不会被使用。状态 0 用于成功地终止程序。
     *
     * die() 函数输出一条消息，并退出当前脚本
     * 该函数是 exit() 函数的别名
     */
    $site = "http://www.w3school.com.cn99/";
    
    fopen($site, "r") or exit("Unable to connect to $site");
    
    // 状态 0 用于成功地终止程序
    if(0)
    {
        echo 'Hello';
    }
    else
    {
        exit("Unable exit!"); // 后面脚本就不执行了
        die("Unable die!");
    }
    // exit()可以用来调试程序 让程序运行到你指定的地方
}
function_name_die();
echo "\n";
