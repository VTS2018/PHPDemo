<?php
/*
 * 学习要点
 * 1.带参数的构造函数
 * 
 * 2.一旦定义有参数的构造函数时：类实例化是必须带参数；除非设置参数默认值$var=''
 * 
 * 3.定义和类同名的函数:发现无法使用实例调用这个方法
 * 
 * 知识点：在PHP4中也提供了构造函数，但使用的是与类同名的类方法，在PHP5仍能兼容这种做法，
 * 当一个类中没有包含__construct时，会查找与类同名的方法，
 * 如果找到，就认为是构造函数
 * 两个可以共存:但是本身没有什么用
 */
class Man
{
    var $name='';
    var $age=0;
    function __construct($var='')
    {
        $this->name=$var;
    }
    //为了兼容PHP4
    function Man($name='Tom',$age=18)
    {
        $this->name=$name;
        $this->age=$age;
    }
    
    function getName() {
        return $this->name;
    }
}

$hanmei=new Man('Han Mei');
echo $hanmei->getName();

echo '<hr />';

$xiaowang=new Man();
echo $xiaowang->getName();
echo '<hr />';

$xiaoli=new Man('xiaoli',19);
echo $xiaoli->age;
?>