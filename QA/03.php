<?php
// 问题 ：关于直接编写在if判断中的变量 是全局变量吗？

// 首先 如果你直接输出一个变量 肯定是有问题
// echo $counter;//Undefined variable

// 然后
if(true)
{
    $counter = 123;
}
// 输出 123
echo $counter;

// 但是还是建议变量先定义初始化 然后引用在使用