<?php

/*
 * 学习要点
 * 1.类的继承：extends
 * 2.访问基类的属性和方法$this；parent
 * 3.override:定义同名基类方法，直接覆盖掉基类的方法
 * 4.$this：访问基类方法【普通和静态方法都可以调用】
 * 5.parent:访问基类方法【在覆盖掉基类同名方法后,】
 * 6.子类实例对象和类名都可以调用基类的静态方法
 * 7.总结：PHP的面向对象比较松散
 * 8.PHP当中不提供方法重载的功能【JavaScript是通过参数的个数来间接实现方法重载】
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

// 车手继承Car类
class User extends car
{

    // 定义档位和速度
    public $speed = array(
        1 => 50,
        2 => 100,
        3 => 260,
        4 => 300
    );

    public $user = '';

    public $run = TRUE;

    function getSpeed($level)
    {
        return $this->speed[$level];
    }

    function setUser($user)
    {
        $this->user = $user;
    }

    function getWheel()
    {
        echo 'user->getWheel';
        echo parent::getWheel();
    }

    function useBaseFunction()
    {
        //
        $this->setWheel();
    }
}

$xiaowang = new User();
$xiaowang->setUser('I am xiaowang');
echo $xiaowang->user;
echo '<hr />';

echo $xiaowang->getSpeed(2);
echo '<hr />';
$xiaowang->getWheel();

// 使用基类方法
echo '<hr />';

// 访问基类静态方法
$xiaowang->getStatic();
User::getStatic();
echo '<hr />';

// 访问实例方法
$xiaowang->setWheel();
echo $xiaowang->getWheel();

echo '<hr />';

?>