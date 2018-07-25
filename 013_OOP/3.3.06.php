<?php

/*
 * 学习要点
 * 1.对象序列化
 * 2.对象反序列化
 * 
 * serialize
 * unserialize
 * 
 * _sleep() 方法 指定序列化的属性
 * _wakeup()方法
 * 
 * final：阻止一个类被继承
 * 
 */

require_once '../common/Man.php';

$boy=new Man();
$str=serialize($boy);
echo $str;
echo '<hr />';

$newboy=unserialize($str);
$newboy->getInfo();
echo '<hr />';

//WordPress中图片的meta信息
$imginfo='a:5:{s:5:"width";i:600;s:6:"height";i:825;s:4:"file";s:13:"2016/07/1.jpg";s:5:"sizes";a:3:{s:9:"thumbnail";a:4:{s:4:"file";s:13:"1-140x100.jpg";s:5:"width";i:140;s:6:"height";i:100;s:9:"mime-type";s:10:"image/jpeg";}s:6:"medium";a:4:{s:4:"file";s:13:"1-189x260.jpg";s:5:"width";i:189;s:6:"height";i:260;s:9:"mime-type";s:10:"image/jpeg";}s:5:"large";a:4:{s:4:"file";s:13:"1-364x500.jpg";s:5:"width";i:364;s:6:"height";i:500;s:9:"mime-type";s:10:"image/jpeg";}}s:10:"image_meta";a:12:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}}}';
$img=unserialize($imginfo);
print_r($img);
?>