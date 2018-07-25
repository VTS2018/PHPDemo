<?php
/*
 * 学习要点
 * 如何定义一个函数
 */
@header('Content-Type: text/html; charset=UTF-8');

// 用户定义函数
function doSomeThing($time=123)
{
    $string = "距离登录月球还有";
    echo $string . $time . "分钟";
    return;
}

doSomeThing();
echo "<hr />";
doSomeThing(10);
?>