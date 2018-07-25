<?php
/*
 * 学习要点
 * 1.抽象类
 * 2.抽象方法
 * 3.abstract
 * 
 */

abstract class Personal
{
    var $age=0;
    var $name='';
    
    abstract function show();
    abstract function setName($name);
    abstract function getName();
    
    function setAge($age) {
        $this->age=$age;
    }
}

class Test extends Personal
{
    function show()
    {
       echo $this->getName().$this->age;
    }
    
    function setName($name)
    {
        $this->name=$name;
    }
    
    function getName() {
        return $this->name;
    }
}

$newTest=new Test();
$newTest->setName('小王');
$newTest->setAge(18);
$newTest->show();
?>