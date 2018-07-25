<?php
/*
 * 学习要点
 * 
 * 1.PHP中类的访问修饰符
 * 2.默认访问是：public
 * 
 * 3.public【公开】 protected【受保护】 private【私有】
 * 4.private:只能在类的内部访问
 * 5.protected：只能在类内部和子类中访问
 * 
 * 总结类的访问范围：类内部；类外部；子类
 */

class Man
{
    public $country='china';
    protected $name='Tom';
    private $sex='Man';
}
class Boy extends Man
{
    function getName()
    {
        $this->name;//protected 在子类中访问
    }
}
$han=new Man();
$han->country;//只能访问$country

$mei=new Boy();
//$mei->name;//无法访问

?>