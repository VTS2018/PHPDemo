<?php

/*
 * 学习要点
 *
 * 01.类的创建
 * 02.类的属性定义
 *
 * 03.var
 * 04.public
 * 05.$this
 *
 * 06.静态属性
 * 07.静态方法
 *
 * 08.类的实例化
 * 09.调用静态属性 函数
 * 10.调用普通属性 函数
 *
 * 扩展学习
 * 定义属性要求
 * 1.不能使用定界符
 * 2.不能使用表达式
 * 3.不能跟括号
 * 4.不能拿其他变量来赋值当前属性
 * 否则将会当做字符串来处理
 *
 * 总结：类的主家庭成员：属性、方法、常量、静态成员
 */
class car
{

    // var 定义属性
    var $wheel = 0;

    // public 定义一个全局属性
    public $run = FALSE;

    // 静态属性
    public static $count = 100;

    // 设置属性
    public function setWheel()
    {
        $this->wheel = 4;
    }

    // 获得属性
    public function getWheel()
    {
        return $this->wheel;
    }

    // 静态方法
    public static function getStatic()
    {
        echo 'I am a static funciton';
    }

    public static function are_you_happy($arg = 'hello')
    {
        if($arg != 'hello')
        {
            return $arg;
        }
        else
        {
            return "good";
        }
    }
}

$red = new car();
// 设置变量值
$red->setWheel();
// 输出变量值
echo $red->getWheel();
echo '<hr />';
print_r($red);
echo '<hr />';

// 显示变量定义
var_dump($red);
echo '<hr />';

echo '<调用静态方法>';
car::getStatic();
echo '<br />';

echo '<调用静态方法>';
echo car::are_you_happy('tom');
echo '<br />';

echo '<调用静态属性>';
echo car::$count;
?>