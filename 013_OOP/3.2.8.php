<?php

/*
 * 学习要点
 * 1.类中的常量
 * 2.const
 * 3.self
 */
class Man
{
    const SEX = "man";

    function getInfo()
    {
        echo self::SEX;
    }
}
$tom = new Man();
$tom->getInfo();
echo Man::SEX;
?>