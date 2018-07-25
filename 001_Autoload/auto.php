<?php

/**
 * 自动载入类函数
 * @param string $classname
 */
function __autoload($classname)
{
    // 建议将类单独组织到一个文件夹下
    require './class/' . $classname . '.class.php';
    // require $classname.'.class.php';
}

// A::staticA();

// 虽然没有显式的 【require】载入B类 但是 __autoload函数会自动帮我们载入B类
B::function_nameB();