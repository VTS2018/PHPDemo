<?php

/*
 * 学习要点
 * 1.构造函数和析构函数
 * 2.__construct
 * 3.__destruct：析构函数不能带参数
 * 4.思考对象的生命周期
 *
 * 5.子类继承父类之后
 * 知识点：PHP不会自动调用父类的构造函数(不支持构造函数重载)，必须使用parent关键字显式地调用
 * 当然也可以调用与该实例没有任何关系的其它类的构造函数。只需在__construct()前加上类名即可。如：otherClassName::__construct();
 */
class base
{

    function __construct()
    {
        echo 'This is base __construct';
    }

    function show()
    {
        echo 'This is base show';
    }

    function __destruct()
    {
        echo 'This is base __destruct';
    }
}

class child extends base
{

    function __construct()
    {
        echo '在子类里面访问基类的构造函数';
        parent::__construct();
    }

    function show()
    {
        echo 'This is child Show';
    }

    function __destruct()
    {
        echo 'This is child __destruct';
        parent::__destruct();
    }
}

// $base=new base();
/*
 * This is base __construct
 * This is base __destruct
 */
echo '<hr />';

$newChild = new child();
echo '<hr />';

?>