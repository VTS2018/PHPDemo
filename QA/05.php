<?php

function static_function()
{
    // 问题：在PHP函数中使用静态变量 静态变量会如何？
    // static 来声明静态变量
    static $i = 1; // 首先声明并初始化了变量值
    $i = $i + 1;
    echo $i;
}

// 多次调用 说明变量 $i 被存储到了 服务端内存一个固定的地方
static_function(); // 2

static_function(); // 3

static_function(); // 4