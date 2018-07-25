<?php
/*
 * 学习要点
 * 
 * 1.定义接口
 * 
 * 2.接口继承接口：使用extends关键字
 * 
 * 3.类继承接口：使用implements管禁止
 * 
 * 4.类实现接口方法
 * 
 * 5.类继承多个接口：使用逗号分隔开
 * 
 */

interface Human
{
    const name='';
    function show();
    function setname($name);
    function getname();
}

interface People
{
    function getstatus();
    function setstatus($status);
}

interface Man extends Human
{
    function setage($age);
    function getage();
}

class Test implements Human,People
{
    var $name='';
    var $status='';
    
    function show()
    {
        echo $this->name;
    }
    
    function setname($name)
    {
        $this->name=$name;
    }
    
    function getname()
    {
        return $this->name;
    }
    
    function setstatus($status)
    {
        $this->status=$status;
    }
    function getstatus()
    {
        return $this->getstatus();
    }
}

$test=new Test();
$test->setname('小王');
echo $test->getname();
echo '<hr />';

$test2=new Test();
$test2->status='Sleep';
echo $test2->status;

?>