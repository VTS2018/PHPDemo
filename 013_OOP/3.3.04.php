<?php
/*
 * 学习要点
 * 1.克隆对象 _clone
 * 2.$boy2=clone $boy1; 克隆一个对象到另一个变量里面
 * 3.函数的_clone方法，在对象被克隆时自动调用
 * 
 * 扩展：单例模式实现，私有化构造函数；和私有化_clone
 */
class Humn
{
    var $name='';
    
    function __clone()
    {
         //克隆时对属性做一些修改
         $this->name='我是克隆对象'.$this->name;
    }
}

$boy1=new Humn();
$boy1->name='胡汉三';
echo $boy1->name;

echo '<br />';
$boy2=clone $boy1;
echo $boy2->name;

?>