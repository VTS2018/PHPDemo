 <?php
/*
 * 学习要点
 * 1.在程序流程控制语句内定义函数
 * 2.在函数中定义一个函数
 * 3.在函数中定义一个类
 * 4.注意以上的定义：在使用的时候注意调用的先后顺序
 */

@header('Content-Type: text/html; charset=UTF-8');

// 无参函数
function onvar()
{
    echo "无参函数<br />";
    return;
}

// 调用
onvar();

// 返回值函数
function returnValue()
{
    return true;
}

// 在程序流程控制语句中直接使用带返回值的函数
if(returnValue())
{

    // 定义在程序流程控制语句内的函数
    function inFunction()
    {
        echo "根据条件定义的函数<br>";
    }
}

// 当returnValue()函数返回TRUE值时,才可以调用inFunction()函数
inFunction();

// 定义一个函数
function base()
{

    // 在函数中定义一个函数
    function offset()
    {
        echo "在函数内部定义的函数<br>";
    }

    // 在函数中定义一个类
    class subClass
    {

        // 在类里定义一个函数
        function subShow()
        {
            echo "在函数内部定义的类里的函数";
        }
    }
}
echo '<hr />';

// 先调用外部函数
base();

// 再调用内部函数
offset();

// 函数运行后,初始化subClass
$newClass = new subClass();

$newClass->subShow();

