<?php
class Man
{
    /*
     * 学习要点
     *
     * 自动载入类
     * 类的名字和 文件的名字一致 ,一个类 一个PHP文件
     */
    public $country = 'china';
    
    protected $name = 'Tom';
    
    private $sex = 'Man';
    
    function getInfo($param = '')
    {
        echo $this->country . ' ' . $this->name . ' ' . $this->sex;
    }
}