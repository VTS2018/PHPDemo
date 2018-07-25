<?php
/*
 * 学习要点
 * 1.自动加载对象 _autoload
 * 
 * 2.沒有完全运行成功
 * 
 * 3.在CLI模式下运行PHP脚本的话这个方法无效
 * 
 * 4.require_once
 */


 /*function _autoload($classname)
 {
     require_once($classname.'.php');
 }*/

 require_once '../common/Man.php';
 
 if (file_exists('Man.php'))
 {
     echo 'Yes';
 }
 
 $liu=new Man();
 $liu->country='cn';
 echo $liu->country;

?>